# Nuvara Retail — design specification

Status: proposed implementation specification, not implemented UI.

## Creative direction

Warm, precise, and understated. Use the existing forest-green and ivory identity consistently across shopping and operations. The storefront can be expressive; the cashier screen prioritizes stable controls, legible totals, and immediate feedback.

## Design tokens

| Token | Starting value | Usage |
| --- | --- | --- |
| canvas | #FAF6EF | Warm application background |
| surface | #FFFFFF | Cards, receipt, forms |
| surface-muted | #F3EEE3 | Secondary regions |
| text-primary | #211D1A | Main text |
| text-secondary | #635C53 | Supporting text |
| brand | #1F3A2E | Primary actions and navigation |
| brand-hover | #2C4B3C | Hover state |
| accent | #B8863B | Decorative brass details; verify contrast before text usage |
| border | #E7DFCF | Dividers and subtle outlines |
| success | #166534 | Confirmed successful state |
| warning | #92400E | Attention required |
| danger | #B91C1C | Errors and destructive actions |

Status always includes text/icon as well as color. Verify final contrast in both themes; these are starting tokens, not a completed accessibility certification.

- Inter for operational UI; tabular numerals for money and quantities.
- Fraunces reserved for storefront/editorial headings.
- Spacing scale: 4, 8, 12, 16, 24, 32, 48, 64 px.
- Radius: 8 px inputs; 12 px cards; 16 px dialogs. Pill shapes for filter chips only.
- Minimum 44 px touch controls; 48–56 px primary cashier/payment controls.
- Visible focus ring and persistent field labels. Errors appear beside fields and in a useful submit summary.
- Dark mode follows the same hierarchy; avoid blanket color overrides and low-contrast brass text.

## Information architecture

| Area | Proposed routes | Layout |
| --- | --- | --- |
| Storefront | Existing shop/product/cart/account routes | Existing public navigation, refined |
| POS | /pos, /pos/sales, /pos/shift | Compact operational layout without shop footer |
| Management | /manage, /manage/products, /manage/inventory, /manage/customers, /manage/reports, /manage/staff, /manage/settings | Sidebar and content workspace |

These are route paths within the current router. Changing hash routing or deployment rewrites is a separate migration decision. Existing Laravel admin screens remain usable until replacement screens have equivalent functionality.

## Cashier screen

Desktop starting proportions: 80 px navigation rail, flexible product canvas, 360–400 px receipt. Use height-aware scrolling in the product region and receipt lines; keep totals and Charge visible.

```text
┌────────────────────────────────────────────────────────────────────┐
│ NUVARA   Main store · Register 01       Connection status   Cashier │
├────────┬────────────────────────────────────┬───────────────────────┤
│ Sell   │ Search products or scan barcode    │ Current sale · #draft │
│ Sales  ├────────────────────────────────────┤ Walk-in customer   +  │
│ Shift  │ All  Electronics  Fashion  Home    ├───────────────────────┤
│        │                                    │ Product name          │
│ Manage │ Product       Product      Product │ Variant · price       │
│        │ Product       Product      Product │ −  quantity  +        │
│        │ Product       Product      Product │                       │
│        │                                    ├───────────────────────┤
│        │                                    │ Subtotal / discount   │
│        │                                    │ Tax                   │
│        │                                    │ Total                 │
│        │                                    │ Hold       Charge     │
└────────┴────────────────────────────────────┴───────────────────────┘
```

Product tiles: restrained image, two-line name, price, SKU, stock state. Adding an item must not navigate away. A variant dialog appears only when a required choice is missing. Sold-out items remain identifiable and explain why they cannot be added.

Search supports typing and keyboard-wedge scanners without stealing focus from quantity/payment inputs. Implement shortcuts only outside editable fields and document them in a small help dialog.

### Payment

Show amount due prominently, tender selector, cash received, exact-cash shortcut, change due, and one final action. Validate cash received before submission. Disable duplicate submission while pending; server idempotency remains mandatory. On an uncertain network result, recover/check the original request rather than creating a new payment.

### Receipt and returns

Receipt: store identity, timestamp/timezone, receipt number, cashier/register, SKU/variant lines, discount/tax breakdown, total, tender, cash/change, and configured return information. Print styles support the chosen paper size; prototype 80 mm but verify hardware. Do not place decorative animation in print output.

Return: locate original sale, select eligible quantities, show refundable amount, capture reason and restock choice. Restricted actions explain permission requirements and do not rely solely on hidden buttons.

## Management dashboard

First row: net sales, completed sales count, average sale, low-stock count. Each metric has a clear period and definition. Below: sales trend and tender breakdown; recent sales and inventory alerts. Empty periods show zero/empty states, never invented revenue.

Use a consistent table system: search, relevant filters, readable columns, pagination, explicit row actions, and selection feedback. Keep key stock and currency numbers aligned. Mobile tables use intentional scrolling or structured cards instead of squeezing every column.

## Storefront redesign

1. Compact shipping announcement with truthful terms.
2. Clean header with real catalog search, categories, account, and cart.
3. One art-directed hero: correct product image, concise headline, primary CTA.
4. Compact service assurances with accurate copy.
5. Editorial category cards and a focused product selection.
6. Optional single product story using scroll progression.
7. Genuine reviews when available; a useful alternative when absent.
8. Functional footer and clear support, shipping, returns, and privacy destinations.

Avoid the current mismatch between the audio hero and watch image. Prefer consistent image aspect ratios, fewer badges, and a deliberate content hierarchy over adding decorations to every section.

## Motion choreography

| Event | Duration target | Treatment |
| --- | --- | --- |
| Button press | 120 ms | Small visual compression or color feedback |
| Added item | 150–180 ms | New receipt row highlight; quantity updates immediately |
| Drawer/dialog | 180–250 ms | Small translation and opacity |
| Form error | Immediate | Inline text and focus; no shaking |
| Payment confirmed | 250–350 ms | Brief checkmark, then stable receipt |
| Storefront reveal | 400–600 ms | Once-per-section opacity and small translation |
| Product story | Scroll-linked | One bounded sequence; normal page scrolling remains available |

- Centralize Motion configuration and respect reduced motion in CSS and JavaScript.
- Never animate the amount being read during payment, move a Charge button, or delay the next scan.
- No looping cashier decorations, cursor trails, scroll hijacking, or full-screen transition gates.
- Prefer transforms and opacity; avoid layout-heavy animations.
- Pause offscreen decorative rendering. Load 3D only on a deliberate product-view action.
- Frame sequences require compressed assets and a static poster; mobile/reduced-motion fallback is mandatory.
- AR is a later optional product feature, not a prerequisite for a polished POS.

## Visual and interaction acceptance checklist

- Review at 1440 px desktop, 1024 px landscape tablet, 768 px, and 390 px mobile, including short viewport heights.
- No horizontal page overflow, hidden totals, clipped translated labels, or overlapping dialogs.
- Keyboard flow covers search, variant selection, cart editing, payment, and returning to a fresh sale.
- Dialogs trap focus appropriately, restore it on close, and have accessible names.
- Zoom to 200%; verify content and controls remain available.
- Check normal, hover, focus, disabled, loading, empty, error, offline, and success states.
- Check English, Bengali, and Arabic/RTL layouts using actual translated content.
- Verify reduced-motion preference and contrast in both themes.
- Evaluate on representative cashier hardware; target immediate local feedback and smooth motion, measured rather than claimed.
- Capture and review implemented screens before marking the visual phase complete.

## Implementation boundaries

Build shared primitives first, then complete one screen at a time. Keep business calculations in the server domain layer, rendering in components, and draft state separate from confirmed records. Avoid introducing GSAP or a 3D runtime into the cashier bundle. Existing Framer Motion is the default animation tool.
