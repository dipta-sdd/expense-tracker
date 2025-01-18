# Expense Tracker

**Contributors:** Sankarsan Das
**Tags:** expense, tracker, finance, budget
**Requires at least:** 5.0
**Tested up to:** 6.7
**Stable tag:** 1.0.0
**License:** GPLv2 or later
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html

Track your expenses with ease using this WordPress plugin. Submit, categorize, and manage your spending effectively.

## Description

This WordPress plugin is designed to help you easily track your expenses, categorize them, and get a clear view of where your money is going. Whether you're managing personal finances or keeping track of business spending, this plugin has got you covered.

## What Can It Do?

- **Easy Expense Submission:** Quickly add new expenses using a simple form right on your website.
- **Clear Expense Lists:** See all your expenses in a well-organized list, with options to filter and sort.
- **Category Power:** Create and manage expense categories to keep everything tidy.
- **Admin Control:** Manage all expenses and categories from a dedicated admin area.
- **Shortcodes for Flexibility:** Use shortcodes to display the expense form and list on any page or post.
- **Customizable Display:** Tweak how your expenses are shown to fit your needs.

## Installation

1.  **Upload:** Drop the `expense-tracker` folder into your `/wp-content/plugins/` directory.
2.  **Activate:** Head over to the 'Plugins' menu in WordPress and activate the plugin.
3.  **That's It!** The plugin will set up everything it needs automatically.

## How to Use It

### Shortcodes - Your Magic Tools

- `[expense_submission_form]` - This displays the expense submission form.
  - **Optional Attributes:**
    - `category_id`: Pre-select a category in the form.
    - `hide_category`: Hide the category dropdown if you don't need it.
- `[expense_tracker_expenses]` - This shows the expense list.
  - **Optional Attributes:**
    - `category_id`: Filter expenses by a specific category.
    - `status`: Filter expenses by their status (pending, approved, rejected).
    - `sort_by`: Sort expenses by date or amount (date_asc, date_desc, amount_asc, amount_desc).
    - `filter`: Enable or disable filter.

### Admin Area - Where the Magic Happens

- Look for the "Expense Tracker" menu in your WordPress admin panel.
- From there, you can manage all your expenses and categories.

## Changelog

### 1.0.0

- The very first version of the Expense Tracker plugin!
- Includes everything you need to submit, list, and manage expenses and categories.
