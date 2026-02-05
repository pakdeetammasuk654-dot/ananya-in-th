## 2025-05-15 - Scoping legacy CSS for new interactive elements
**Learning:** Adding new interactive elements (like password visibility toggles) to legacy forms that use generic CSS selectors (e.g., `button`) can cause visual regressions. Scoping existing styles to specific attributes (e.g., `button[type="submit"]`) ensures that the new elements don't inherit unintended styles.
**Action:** Before adding new buttons or interactive elements, audit the existing CSS for generic selectors and scope them as needed.

## 2025-05-15 - Localized ARIA labels for better accessibility
**Learning:** For applications targeting specific language regions (e.g., Thailand), providing localized ARIA labels (e.g., "แสดงรหัสผ่าน" instead of "Show password") significantly improves the experience for screen reader users.
**Action:** Always use localized strings for ARIA labels in region-specific applications.
