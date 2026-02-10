# Logic Summary for Ananya Project Migration

## 1. Authentication System (ระบบสมาชิก)
- **Table**: `membertb`
- **Logic**:
    - `Login`: Matches `username` and `password`. The original PHP uses plain text or `LIKE` comparisons. The Go version will use `bcrypt` for password hashing and verification.
    - `Register`: Checks if `username` already exists. Inserts new user data including `realname`, `surname`, `birthday`, and calculation of age.
    - `Update`: Updates user profile and recalculates age.

## 2. Article Management (ระบบจัดการบทความ)
- **Tables**: `articles` (newer) and `topictb` (older/legacy topics).
- **Logic**:
    - `CRUD`: Supports listing articles, viewing details by ID or slug, creating new entries, updating existing ones, and deletion.
    - `Images`: Handles image uploads (base64 or file upload) and stores them in `public/uploads`.
    - `JSON API`: Provides structured data for the mobile application (Android).

## 3. Thai Calendar Helper (ระบบปฏิทินไทย)
- **Logic**:
    - **Lunar Date Calculation**: Based on a reference epoch: January 1, 2023 (Waxing 10, Month 2, BE 2566).
    - **Wan Pra (Holy Days)**: Occur on Waxing/Waning 8th and 15th days. Handles 14th day for short months (odd months).
    - **Auspicious Days (Kallayok)**: Calculates `Tongchai` (Victory) and `Atipbadee` (Director) days based on the lunar month and day of the week.

## 4. Name Analysis (การวิเคราะห์ชื่อ)
- **Logic**:
    - **Numerical Value**: Assigns numerical values (1-9) to each Thai character.
    - **Shadow Numbers**: Assigns different sets of values based on "astrological stars".
    - **Karakini**: Identifies forbidden characters based on the user's birthday.
    - **Summation**: Calculates total values for name, surname, and combined name-surname to determine "miracle" meanings from the `numbers` table.

## 5. Miscellaneous
- **Bag Colors**: Recommends bag colors based on user's age.
- **Lucky Numbers**: Generates or retrieves daily lucky numbers.
- **Phone Number Sell**: Management of auspicious phone numbers for sale.
