/**
 * WordPress dependencies
 */
const { test, expect } = require('@wordpress/e2e-test-utils-playwright');

test.describe('Validate Rtsocial Settings', () => {
    test.beforeEach(async ({ admin }) => {
        await admin.visitAdminPage('options-general.php');
    });
    test('Check rtSocial Options', async ({ admin, page, editor }) => {
       // Goto rtSocial options
        await page.locator("role=link[name='rtSocial Options'i]").click();
       // Check Placement Settings
        const placementSettings = page.locator("#rtsocial-placement-settings-row > td > fieldset")
         expect(placementSettings).not.toBeNull();
       // Check Button Style
        const buttonStyle = page.locator("table[id='rtsocial-button-style-inner']")
        expect(buttonStyle).not.toBeNull();
        // Check Alignment Settings
        const alignmentSetting = page.locator("input[id='align_left_check']");
        expect(alignmentSetting).not.toBeNull();
        // Active Button
        const activeButtons = page.locator("ul[id='rtsocial-sorter-active']");
        expect(activeButtons).not.toBeNull();
        // Validate twitter Setting
        const twitterHandle = page.locator("input[id='tw_handle']");
        expect(twitterHandle).not.toBeNull();
        // Verify token
        const facebookToken = page.locator("input[id='fb_access_token']");
        expect(facebookToken).not.toBeNull();
    });
});