# Hello Transport website — requested changes

**Status:** requirements only. No code changed.
**Source:** client email + 13 screenshots in `C:\Users\Dell\Downloads\hello`, 12 October 2026.
**Site:** hellotransport.com (public site served from the `washinton_agent` repo).

These are independent of the brand split, but they land on the Hello side of it — so they should be
built **after** the repo fork, or they will have to be applied twice.

---

## W-01 — Agent programme teaser on the home page

Add a section partway down the home page promoting "Earn with Hello Transport", with a button
through to the new page (W-02).

**Reference:** `For home page.JPG` — the client circled the "Agent Program" card on
protrucklogistics.org. That card style is the target: icon, heading, two lines, one button.

**Acceptance**
- Section appears on scroll, above the "Trusted By" band
- Button reaches W-02
- Works at phone width

---

## W-02 — New page: Earn with Hello Transport

A recruitment landing page for sales agents.

**Also:** remove **Login** and **Signup** from the public site header; this page becomes the way in.

**Sections, in order**

1. Overview
2. Why work with us (benefits)
3. How it works — `apply → get reviewed → get approved → onboarding → start selling → earn`
4. Ready to apply (the W-03 form)
5. Already part of our team? → login page
6. FAQs (campaigns, hiring, experience)

**References the client supplied**

- protrucklogistics.org/agents.html — the "How to Become an Agent" numbered row (`how to become an agent.JPG`) is the model for section 3
- atransllc.com
- yescontainers.com/careers/sales-agents
- air7seas.com/freight-agent

Full-page captures of all four are saved alongside the screenshots.

**Open:** does removing Login/Signup from the header apply to every page or only the home page?

---

## W-03 — Signup / application form

Heading: **Apply for an Opportunity** — *"Complete the form below. It takes only a few minutes."*

| Group | Fields |
|---|---|
| Contact Information | First Name · Last Name · Email · Phone · Country · City |
| Time Zone | auto-detected from location |
| Experience | Previously worked with U.S. customers or businesses? **Yes / No**<br>Can you work U.S. time zones when required? **Yes / No / Flexible** |
| Additional | Resume/CV upload *(optional)* · LinkedIn URL *(optional)* · "Tell us about yourself" free text |
| Legal | Terms & Conditions — supplied as `Terms & Condition - Signup page.pdf` |

Submit button: **Apply to Join the Network**

**Note:** this replaces the current `hellotransport.com/register` flow, which today produces the
"ACCOUNT CREATED — pending admin approval" screen (`account created.JPG`). The new flow is an
*application*, not an account — see W-04. Confirm whether an account is still created at this point
or only after approval. **This changes the onboarding pipeline and needs an explicit answer.**

---

## W-04 — Application submitted screen

Replaces the current "Account Created" screen.

> **Application Submitted**
> Thank you for applying to join our team.
> Your application has been successfully received and is now under review.
> A confirmation email has been sent to *{email}*.
> Our recruitment team will review your application and contact you if your profile matches an available opportunity.
>
> **Questions?** For recruitment-related questions, contact **info@hellotransport.com**

---

## W-05 — Login page wording

Reference: `login.JPG`, annotated **a**–**d** by the client.

| | Now | Change to |
|---|---|---|
| **a** | "Sign in to access your portal and manage your transport operations." | "Your Hello Transport workspace for managing orders, customers, and opportunities." |
| **b** | "SIGN IN — Enter your credentials to access your account" | "Access Your Account — Enter your credentials to continue to your account." |
| **c** | Real-time order tracking<br>Nationwide coverage<br>Secure & reliable platform<br>24/7 support team | Manage leads, customers & opportunities<br>Create quotes and manage orders<br>Track shipments and customer activity<br>Secure & reliable workspace |
| **d** | "New agent or carrier? Create Account" | "New to the Hello Transport network? Create Account" → **W-02**, plus "← Back to Homepage" |

---

## W-06 — Blog section

Add a blog to the site. Use the existing shipa1.com blog as the working model.

**Open:** who writes the posts, and is an admin screen needed, or is static/seeded content enough
for launch?

---

## W-07 — Quote forms per service

**Two separate problems.**

**(a) Every service shows the car form.** On `/services/boat`, `/services/rv` and the rest, the
"Get a Quote" button opens the generic **car** quote form (`our services qoute form.JPG`).
Each service must open the form for *that* service, matching ShipA1's equivalents.

ShipA1's boat form (`boat qoute form.JPG`, `shipa1.com/transport-services/boat`) asks for:
Category · Name · Phone · Email · Pickup · Delivery · **Year / Make / Model · Length / Width /
Height / Weight · Trailer Type · Condition · Load & Unload Method** · "Is your boat on trailer?" ·
Modified? · Available at Auction? · Image upload.

Services to cover: **boat, RV, heavy equipment, freight, RoRo**, plus any other service page.

**(b) Remove "Vehicle Price".** Delete that field from every car-information quote form on the site
(`vehicle price remove.JPG`).

> This matches work already done in the Washington CRM, where Vehicle Price was hidden from the
> quote screens in the July batch. Doing the same on the public site keeps the two consistent.

---

## Sequencing note

W-07(b) is a deletion and can ship immediately. W-01 → W-05 are one coherent piece of work and
should ship together, since W-02 depends on removing the header links and W-03/W-04 replace the
current signup. W-06 is independent and the least urgent.

---

## Open questions — collected

1. Header Login/Signup removal: all pages, or home only?
2. Does W-03 still create a pending account, or only an application record?
3. Blog: who authors, and is an admin UI required?
4. Which exact service pages exist today that need their own quote form?
5. Do the new application records feed the existing CrazyRays-style campaign/applications tables, or
   does Hello need its own?
