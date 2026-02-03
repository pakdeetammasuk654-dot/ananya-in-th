## 2026-02-03 - [Accessibility in Thai localized apps]
**Learning:** In applications where the primary language is Thai, it is essential to set the `html` `lang` attribute to `th` and provide localized `aria-label`s for interactive elements. Icon-only buttons (e.g., password visibility toggles, menu toggles) are particularly problematic for screen readers if they lack descriptive, localized labels.
**Action:** Always verify that the `html` `lang` attribute matches the content and provide localized `aria-label`s for all icon-only buttons.

## 2026-02-03 - [Style Isolation for Form Enhancements]
**Learning:** When adding micro-UX elements like password toggles to legacy forms with generic CSS selectors (e.g., `button`), it's important to scope existing styles to `button[type="submit"]` to avoid style leakage and layout breakage for new functional buttons.
**Action:** Use more specific CSS selectors when enhancing legacy forms to ensure new interactive elements are not adversely affected by existing global styles.
