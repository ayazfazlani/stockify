# Subscription Workflow for SaaS (complete, scalable, trackable)

This document defines a complete subscription workflow and how to track it as a SaaS owner. It focuses on event-driven, auditable, and scalable practices: signup → trial → billing → lifecycle (upgrade/downgrade/cancel) → retention/churn handling → analytics & ops.

---

## 1. High-level stages
- Lead capture / account creation (email, SSO)
- Plan selection (free/trial/paid)
- Trial activation (optional)
- Payment setup (card/ACH/invoice) and billing subscription creation
- Periodic billing / invoicing
- Usage metering (if metered billing)
- Upgrades / downgrades / proration
- Failed payment handling (dunning)
- Cancellation & data retention
- Re-activation / win-back
- Billing reconciliation & refunds

---

## 2. Goals & SLAs
- Accurate revenue recognition (MRR/ARR)
- Near-real-time subscription state across systems (< 1s–5s eventual consistency)
- Idempotent billing & webhook processing
- Automated dunning with human intervention flags
- Observability: errors, latency, billing failures, churn spikes

---

## 3. Key metrics to track (definitions & formulas)
- MRR (Monthly Recurring Revenue): sum(monthly value of active subscriptions)
- ARR = MRR * 12
- New MRR = MRR from new customers in period
- Expansion MRR = upsells / upgrades
- Churn rate = (churned customers in period) / (starting customers)
- Revenue churn = lost MRR / starting MRR
- LTV = average revenue per user / churn rate
- CAC payback = total acquisition cost / monthly gross margin
- Dunning success rate, failed-payment rate, refund rate

---

## 4. Data model (minimal tables/entities)
- accounts: id, org_name, created_at, status (active/suspended/canceled)
- users: id, account_id, email, role, created_at
- plans: id, name, billing_period, price_cents, features, is_metered
- subscriptions: id, account_id, plan_id, status (trial/active/past_due/canceled), start_date, current_period_start, current_period_end, cancel_at_period_end, billing_provider_id
- invoices: id, account_id, subscription_id, amount_cents, status (draft/paid/failed/refunded), due_date, paid_at
- payments: id, invoice_id, amount_cents, method, status, provider_charge_id, created_at
- usage_records: id, subscription_id, metric, quantity, recorded_at
- events: id, account_id, type, payload(json), created_at, processed (bool)
- audit_log: id, entity_type, entity_id, action, actor_id, metadata, created_at

---

## 5. Event schema & important event names
- Event pattern: {type: "subscription.*", payload: {...}}
- Important events:
  - account.created
  - user.invited / user.accepted
  - plan.selected
  - subscription.created
  - subscription.trial_started
  - subscription.activated
  - invoice.generated
  - payment.succeeded
  - payment.failed
  - subscription.payment_failed (dunning step)
  - subscription.prorated_charge
  - subscription.upgraded / downgraded
  - subscription.canceled
  - subscription.reactivated
  - usage.reported
  - refund.issued
- Example event JSON:
  {
    "type":"subscription.created",
    "timestamp":"2025-10-05T12:00:00Z",
    "account_id":"acct_123",
    "payload": {
      "subscription_id":"sub_456",
      "plan_id":"plan_pro",
      "trial_end":"2025-10-12T12:00:00Z"
    }
  }

---

## 6. Operational workflow (step-by-step)
1. Account creation
   - Validate email/SSO; create account & initial user.
   - Emit account.created.

2. Plan selection & trial
   - Store desired plan; optionally create trial subscription (status=trial) with trial_end.
   - Start usage tracking if applicable.

3. Payment setup
   - On trial end or paid plan selection, capture payment method using a payment provider (Stripe/Adyen/PayPal).
   - Create provider subscription and store provider id.
   - Emit subscription.activated once provider confirms.

4. Periodic billing
   - Use provider webhooks to create invoice records and payments.
   - Mark invoices & payments in DB; emit invoice.generated, payment.succeeded.

5. Failed payments & dunning
   - On payment.failed: schedule retries with exponential backoff and update subscription.status to past_due.
   - Send emails/notifications for each retry stage; escalate to collections/human review after N failures.
   - If unresolved, cancel or suspend subscription at a policy-defined point.

6. Upgrades/downgrades
   - On change: compute proration, call provider API, apply immediate or future period proration.
   - Emit subscription.upgraded / subscription.downgraded and update MRR metrics.

7. Cancellation & retention
   - Allow cancel_at_period_end or immediate cancel with refund policy.
   - On cancel: archive or limit features; keep minimum retention for billing/legal purposes.
   - Emit subscription.canceled.

8. Re-activation
   - Allow re-activation, possibly re-billing or applying promotions. Emit subscription.reactivated.

---

## 7. Instrumentation & tracking approach
- Central event bus: publish every state change event to a message broker (Kafka, RabbitMQ, or managed streams).
- Events are source of truth for analytics and syncing downstream systems.
- Track events with consistent schema and a unique event_id, timestamp, and version.
- Capture context: account_id, user_id, tenant_id, correlation_id, request_id.

Suggested event pipeline:
- App -> produce event -> event bus -> processors:
  - Billing service processor
  - Analytics (Segment / Snowplow)
  - BI ETL -> data warehouse (Snowflake/BigQuery/Redshift)
  - Notifications (email/SMS)
  - Audit log/backup

---

## 8. Webhooks & idempotency
- Use webhooks from payment provider. Always:
  - Validate signature
  - Store raw payload in events table
  - Process idempotently (use provider event id)
  - Acknowledge quickly (200) and process async if heavy

Webhook example minimal requirements:
- Accept: event_id, type, timestamp, signature
- Process: if event_id already seen -> noop

---

## 9. Billing & reconciliation practices
- Keep invoice & payment records normalized and reconciled nightly against provider.
- Run automated reconciliation job:
  - Compare provider invoices/payments vs local invoices/payments
  - Flag mismatches for manual review
- Keep a ledger table for revenue recognition and adjustments
- Support offline/invoice payments with manual reconciliation workflow

---

## 10. Security, compliance & data handling
- PCI scope: do not store card data; use tokenization (Stripe Elements, Checkout).
- GDPR: retention policy, data export & deletion endpoints
- Role-based access for billing operations
- Encrypt sensitive fields at rest
- Audit log for billing changes and refunds

---

## 11. Scaling & architecture patterns
- Microservice separation:
  - Auth & accounts
  - Billing & subscription service
  - Payments adapter (provider integration)
  - Usage collector (high-throughput ingest)
  - Notification service
  - Analytics collector
- Use event sourcing or event bus for cross-service consistency
- Backpressure: usage ingestion -> shard by account -> buffer -> batch writes
- Background workers for heavy work (billing cycles, notifications, reconciliation)
- Idempotent operations and retries with exponential backoff
- Use rate limiting at public APIs per account
- Caching: cache plan catalog & subscription state for read-heavy UX; use short TTL and invalidate via events

---

## 12. Dunning & lifecycle automation
- Dunning stages example:
  - immediate: payment failed -> retry after 1 hour
  - stage 1: 3 retries over 3 days -> notify customer
  - stage 2: escalate, reduced features, final notice at day 7
  - stage 3: suspend/cancel at day 14 -> mark as churned
- Use orchestration engine or workflow tool (Temporal, Durable Functions, or simple state machine) to manage retries and timers

---

## 13. Dashboards & alerts (suggested)
- Dashboards:
  - Real-time MRR & MRR trend
  - New MRR vs churned MRR vs expansion MRR
  - Dunning funnel: failed payments -> retries -> recoveries
  - Refunds and payment disputes
  - Top failed bank/card types
- Alerts:
  - Spike in failed payments (>5% of payments)
  - MRR drop > X% day-over-day
  - Reconciliation failure rate > threshold
  - Long-running billing jobs / stuck webhooks

---

## 14. Testing & rollout
- Unit tests for billing calculations & proration
- Integration tests with payment sandbox
- End-to-end staging environment with real-like webhook flows
- Feature flags for billing model changes; canary releasing to subset of accounts

---

## 15. Implementation checklist (practical)
- [ ] Define plan catalog & pricing model
- [ ] Implement subscription CRUD and state machine
- [ ] Integrate payment provider with webhook handling
- [ ] Build event bus and publish all subscription events
- [ ] Implement invoice & payment ledger + reconciliation job
- [ ] Implement dunning workflow and notifications
- [ ] Add metrics, dashboards, and alerts
- [ ] Implement tests, staging environment, and rollback plans
- [ ] Define retention & compliance policies

---

## 16. Recommended tools & services
- Payment: Stripe (or Adyen/Paypal)
- Message bus: Kafka / RabbitMQ / AWS SNS+SQS
- Workflow: Temporal / Cadence / Durable Functions
- Data warehouse: Snowflake / BigQuery
- Analytics: Looker / Metabase / Redash
- Monitoring: Prometheus + Grafana, Sentry, PagerDuty
- CI/CD: GitHub Actions / Azure DevOps

---

This workflow ensures traceability, scalability, and clear operational playbooks for subscription lifecycle, billing, and analytics. Use event-first design, idempotent processing, background orchestration, and strong observability to scale safely.

## 17. Example implementation — code snippets & end-to-end flow

Summary flow (high level)
1. App action -> produce event to event bus (Kafka/SNS).
2. Webhook from payment provider -> persist raw event -> emit provider event to event bus.
3. Event processors consume events:
  - Billing worker: create/adjust invoices, ledger entries.
  - Dunning orchestrator: schedule retries, send notifications.
  - Analytics ETL: write to warehouse.
4. Nightly reconciliation compares provider data vs local ledger and flags mismatches.

Sequence (detailed)
1. Customer signs up → app creates account in DB → emit account.created.
2. User selects plan → subscription.created (status=trial or pending_payment).
3. Payment method captured → create provider subscription → provider.webhook -> subscription.activated.
4. Billing cycle -> provider sends invoice.created/payment.succeeded -> invoice record created, ledger updated, emit invoice.generated & payment.succeeded.
5. On payment.failed -> subscription.status = past_due, dunning workflow starts (retry/backoff → notify → suspend/cancel).
6. Reconciliation job runs nightly to reconcile provider vs local records.

Minimal TypeScript event producer (Node)
```ts
// event-producer.ts
import { Kafka } from "kafkajs"; // or replace with your bus
const kafka = new Kafka({ brokers: [process.env.KAFKA_BROKER!] });
const producer = kafka.producer();

export async function produceEvent(event: any) {
  await producer.connect();
  const payload = {
   event_id: event.event_id || `evt_${Date.now()}`,
   timestamp: new Date().toISOString(),
   version: "1",
   ...event
  };
  await producer.send({
   topic: "subscription.events",
   messages: [{ key: payload.event_id, value: JSON.stringify(payload) }]
  });
  await producer.disconnect();
}
```

Webhook endpoint (Express) — validate signature, idempotency, store raw payload
```ts
// webhook.ts
import express from "express";
import crypto from "crypto";
import { insertRawEventIfNew } from "./db";

const app = express();
app.use(express.json({ limit: "1mb" }));

function verifySignature(rawBody: string, signature: string, secret: string) {
  const h = crypto.createHmac("sha256", secret).update(rawBody).digest("hex");
  return crypto.timingSafeEqual(Buffer.from(h), Buffer.from(signature));
}

app.post("/webhook/provider", async (req, res) => {
  const sig = req.header("x-provider-signature") || "";
  const raw = JSON.stringify(req.body);
  if (!verifySignature(raw, sig, process.env.WEBHOOK_SECRET!)) {
   return res.status(400).send("invalid signature");
  }

  const providerEventId = req.body.id;
  // insertRawEventIfNew returns false if event already exists (idempotency)
  const inserted = await insertRawEventIfNew({ provider_event_id: providerEventId, payload: req.body, received_at: new Date() });
  if (!inserted) return res.status(200).send("already processed");

  // Publish internal event to bus (async) or mark for workers
  // produceEvent({ type: req.body.type, payload: req.body.data, source: "provider" });

  res.status(200).send("ok");
});

app.listen(3000);
```

Billing worker (consumer pattern) — idempotent processing, ledger writes
```ts
// billing-worker.ts
import { consume } from "./bus";
import { upsertInvoiceFromProvider, createLedgerEntry, markEventProcessed } from "./db";

consume("subscription.events", async (event) => {
  const { event_id, type, payload } = event;
  // idempotency guard at business level
  if (await markEventProcessed(event_id) === false) return; // already processed

  if (type === "invoice.generated" || type === "provider.invoice.created") {
   await upsertInvoiceFromProvider(payload.invoice);
   await createLedgerEntry({
    invoice_id: payload.invoice.id,
    account_id: payload.invoice.account_id,
    amount_cents: payload.invoice.amount_cents,
    posted_at: new Date()
   });
  }

  if (type === "payment.succeeded") {
   await createLedgerEntry({
    invoice_id: payload.invoice_id,
    account_id: payload.account_id,
    amount_cents: -payload.amount_cents, // payment reduces receivable
    posted_at: new Date()
   });
   // update subscription/invoice status etc
  }

  // other event handlers...
});
```

Dunning workflow (schematic, Temporal-like pseudocode)
```ts
// dunning.workflow.ts (pseudocode)
workflow dunningWorkflow(accountId, subscriptionId, initialFailureEventId) {
  attempts = 0
  while (attempts < 5) {
   attempts += 1
   sendEmail(accountId, templateForAttempt(attempts))
   wait(backoffForAttempt(attempts)) // 1h, 1d, 2d, 4d, 7d...
   // check payment status via provider API
   if (paymentRecovered(subscriptionId)) {
    emitEvent({ type: "subscription.payment_recovered", subscription_id: subscriptionId })
    return "recovered"
   }
  }
  // escalate and suspend/cancel after policy threshold
  emitEvent({ type: "subscription.escalated", subscription_id: subscriptionId })
  updateSubscriptionStatus(subscriptionId, "canceled")
  emitEvent({ type: "subscription.canceled", subscription_id: subscriptionId })
}
```

Nightly reconciliation script (Python outline)
```py
# reconcile.py
# Fetch provider invoices via API, fetch local invoices, compare line-by-line
from datetime import date
def reconcile(date_from, date_to):
   provider_invoices = provider_api.list_invoices(date_from, date_to)
   local_invoices = db.query_local_invoices(date_from, date_to)
   mismatches = []
   for p in provider_invoices:
      local = local_invoices.get(p.id)
      if not local:
        mismatches.append(("missing_local", p.id))
        continue
      if p.amount_cents != local.amount_cents or p.status != local.status:
        mismatches.append(("diff", p.id, p.amount_cents, local.amount_cents, p.status, local.status))
   # write mismatches to ops queue/table and alert if > threshold
   db.insert_reconciliation_results(mismatches)
```

SQL snippets: idempotent upsert for events and invoice
```sql
-- events table idempotent insert
INSERT INTO events (event_id, type, payload, created_at, processed)
VALUES ($1,$2,$3,NOW(),false)
ON CONFLICT (event_id) DO NOTHING;

-- invoices upsert (Postgres)
INSERT INTO invoices (id, account_id, subscription_id, amount_cents, status, due_date)
VALUES ($1,$2,$3,$4,$5,$6)
ON CONFLICT (id) DO UPDATE
SET amount_cents = EXCLUDED.amount_cents, status = EXCLUDED.status, due_date = EXCLUDED.due_date, updated_at = NOW();
```

Operational checklist for rolling out code
- Add request and provider signature validation tests (unit + integration).
- Add idempotency integration tests (send duplicate webhook events).
- Canary webhook traffic and provider callbacks to staging first.
- Monitor processing lag, error rates, and reconciliation mismatch counts.
- Add alerting for reconciliation failure rates and dunning escalations.

Notes & best practices (short)
- Treat events as immutable source of truth; store raw provider payloads.
- Make all external calls idempotent and retry-safe.
- Keep business logic in workers/orchestrators (not in webhook sync path).
- Store correlation_id on events and DB writes for tracing.
- Log and surface reconciliation mismatches to human ops with contextual data.

This provides the minimal code + flow to implement the subscription lifecycle, webhook handling, billing processing, dunning orchestration, and nightly reconciliation required for a robust SaaS billing system.