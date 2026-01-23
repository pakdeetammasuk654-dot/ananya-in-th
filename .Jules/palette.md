## 2024-07-25 - Accessible Loading States for Buttons

**Learning:** When implementing a loading state on a button, hiding the button's text with `display: none` makes it inaccessible to screen readers. Users of assistive technology lose context about the button's function and state.

**Action:** For future tasks involving dynamic button states, I will use a dedicated `visually-hidden` class to hide the text. This keeps the text available in the accessibility tree. Additionally, I will toggle the `aria-busy` attribute on the button to programmatically announce its loading state to assistive technologies. This ensures a more inclusive and accessible user experience.