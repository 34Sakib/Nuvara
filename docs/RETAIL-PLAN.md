# Nuvara Retail — implementation plan

Status: Phase 3 complete (shared visual system and POS workspace prototype). Phase 4 begins with real POS endpoints.
Updated: 2026-09-08

## Product decision

Build a web-based retail system connecting the existing online store, a new cashier POS, and management. Preserve React, Laravel, Tailwind, Zustand, localization, and the Nuvara identity. Do not rewrite the project or replace its database wholesale.

The first release serves one retail business and one location. Design database relationships for a location and register without building multi-tenant SaaS, subscriptions, restaurants, tables, or kitchen workflows. These are working defaults, not confirmed business requirements.

### Working defaults

- General merchandise with optional product variants.
- Desktop and landscape tablet cashier use; responsive mobile management and shopping.
- Online-first sales. Disconnected screens show clear status and retain drafts, but cannot claim a sale or payment succeeded.
- Cash is the initial POS tender. Online card/mobile payments require a selected provider and verified integration; remove the simulated card form before operational use.
- Preserve existing USD values during development. Never relabel USD prices as BDT. Currency, tax rules, locale, and receipt identity become store configuration.
- No live charges, messages, deployment, destructive migrations, or production data changes as part of local implementation.
- Browser printing first. Printer and barcode-scanner compatibility must be verified with the eventual hardware.

### Decisions to resolve before the relevant release gate

| Decision | Development default | Needed before |
| --- | --- | --- |
| Retail category | General merchandise | Final cashier usability review |
| Country/currency/tax | Preserve USD; explicit configurable rules | Real transactions |
| Payment provider | Cash POS; no simulated paid status | Online payment release |
| Receipt printer/scanner | Browser print and keyboard-wedge scanning | Hardware pilot |
| Offline selling | Draft persistence only | Any offline-sales claim |
| Store identity and return policy | Clearly identified development values | Customer receipts |

## Core workflow

1. Authorized cashier selects an available register and opens a shift with opening cash.
2. Search or scan an SKU; resolve required variants; add available quantity to the current sale.
3. Optionally select a customer. Walk-in is the default and does not require a shipping address.
4. Edit quantity or remove items; apply only authorized discounts. Server resolves prices, taxes, and totals.
5. Hold the draft or select Charge. The payment screen shows amount due, tender, cash received, and change.
6. On submit, the server validates the shift, permissions, inventory, and request identity; commits the sale, payment record, and stock movements consistently.
7. Display success only after confirmation. Print a receipt or continue to a new sale; printing failure must not create another sale.
8. A permitted staff member can locate a sale, return eligible quantities, record a refund and reason, and choose restock disposition.
9. Closing the shift compares expected and counted cash, records variance, and preserves the audit history.

## Phases and acceptance gates

### Phase 1 — scope and design (complete)

Deliverables: this plan and DESIGN-SPEC.md, including route structure, screen anatomy, motion policy, domain model, and implementation gates.

### Phase 2 — reliable commerce foundation (next)

- Move API origin into environment configuration with a local development default.
- Separate real application data from explicit demo fixtures; correct empty, loading, and unavailable states.
- Replace simulated card entry and inaccurate payment-success/security claims.
- Separate payment and fulfillment state; creation must not imply delivered or paid.
- Calculate money consistently using integer minor units or fixed-decimal arithmetic.
- Resolve variants per line, enforce required selections, and respect price overrides.
- Validate aggregate requested stock inside the transaction using database-appropriate concurrency protection.
- Introduce request idempotency so retries do not create duplicate sales or decrement stock twice.
- Revalidate coupon status, expiry, minimum spend, and usage eligibility server-side; submit the actual selected coupon.
- Replace manual repeated authorization checks with explicit policies/middleware as the new APIs are added.
- Add behavioral tests for totals, coupon rejection, stock rollback, repeated lines, variant isolation, and retries.

Gate: backend commerce tests and frontend build pass; failures cannot create partial orders or false success. Test concurrency on the intended production database before release; SQLite tests alone cannot establish production locking behavior.

### Phase 3 — design system and interactive screens

- Implement shared colors, typography, spacing, buttons, inputs, dialogs, status badges, tables, and keyboard focus.
- Create separate Shop, POS, and Management layouts.
- Build responsive cashier, payment, receipt, refund, inventory, and dashboard views.
- Use clearly marked development fixtures while APIs are pending.
- Implement one consistent motion configuration with reduced-motion support.

Gate: representative sale can be rehearsed with keyboard and touch; no clipped totals, hidden primary controls, inaccessible dialogs, or fake operational results.

### Phase 4 — POS MVP

- Location/register configuration, shifts, product/SKU search, and server-backed held sales.
- Sales and line snapshots, cash tender/change, receipts/reprints, audit events, and staff permissions.
- Wire screens to real endpoints with validation, retry handling, and preserved drafts.

Gate: open shift → sell → receipt → reprint → close shift works end to end. Repeated submit creates one sale; an unavailable backend preserves the draft.

### Phase 5 — connected operations

- Inventory movement ledger shared by online and counter sales.
- Stock receiving, adjustments with reasons, low-stock status, partial returns and refunds.
- Customer history and reports derived from actual settled transactions and refunds.
- Distinguish gross sales, discounts, refunds, taxes, net sales, tender totals, and cash variance.

Gate: online and counter sales reconcile; refund and stock disposition are correct; permissions are enforced server-side.

### Phase 6 — storefront and motion polish

- Correct hero imagery/copy, connect footer destinations, remove unsubstantiated badges and fabricated social proof.
- Refine product photography, category layout, filters, product details, and checkout.
- Add one signature product story and restrained interface motion.
- Lazy-load marketing animation and 3D assets; cashier routes must not load them.
- AR/3D is optional and requires suitable owned/licensed models and a useful product case.

Gate: desktop/mobile visual review, keyboard and reduced-motion checks, production build, and performance measurement on representative devices.

### Phase 7 — pilot and release readiness

- Verify actual scanner/printer behavior, simultaneous cashier use, payment integration if selected, backup/restore, and network recovery.
- Check localization, currency formatting, taxes, authorization, audit history, and receipt accuracy.
- Pilot a full trading day with reconciled totals and documented issues before deployment approval.

Gate: no unresolved transaction-integrity defects; pilot results reviewed. A visual score is subjective and does not substitute for these checks.

## Domain model to introduce incrementally

Retain existing catalog/customer data. Introduce migrations additively after inspecting existing records.

| Entity | Purpose |
| --- | --- |
| Store/location | Identity, currency, timezone, tax and receipt configuration |
| Register | Counter terminal belonging to a location |
| Register session | Cashier, opening float, close count, variance, state |
| Sale/order | Channel, location, cashier/session, customer, totals, payment and fulfillment states |
| Sale/order line | SKU/name/variant/price/tax snapshots and quantity |
| Payment | Tender, amount, state, provider reference, idempotency identity |
| Inventory movement | Product/variant, location, signed quantity, reason and source document |
| Held sale | Recoverable draft; does not silently reserve stock |
| Refund/return line | Original sale/line, quantity, amount, reason, restock choice |
| Audit event | Actor, action, affected record, timestamp and relevant changes |

Decide whether the existing orders table can safely support both channels before introducing a separate sales table. Avoid two independent stock or pricing engines.

## Current baseline

Audit from 2026-09-08: frontend build passed with a roughly 658 KB minified main JS chunk and lint warnings. Two backend example tests passed; they do not cover commerce. Homepage browser review used fallback content with the backend stopped. No production-readiness claim follows from these checks.

## Progress log

- Phase 1: scope, workflows, visual rules, release gates, and domain direction documented.
- Phase 2: checkout quote and idempotent unpaid order flow implemented; stock, variants, coupon rules, and API configuration hardened. Frontend checkout no longer collects simulated card data.
- Phase 3: `/pos` cashier workspace implemented with responsive receipt panel, product search, categories, quantity controls, tender selection, cash/change feedback, motion preferences, and POS-specific layout.
- Phases 4–7: pending. No payment provider, target currency, or hardware has been assumed to be confirmed.
