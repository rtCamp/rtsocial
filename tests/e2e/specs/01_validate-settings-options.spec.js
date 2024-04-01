/**
 * WordPress dependencies
 */
const { test } = require('@wordpress/e2e-test-utils-playwright');
const { RtSocial } = require('../page/RtSocial.js');
test.describe('Validate Rtsocial Settings', () => {
    test('Check rtSocial Options', async ({ admin, page }) => {
        await admin.visitAdminPage("/");
        const rtSocialobj = new RtSocial(page);
       await rtSocialobj.navigateToSettingPage();
       await rtSocialobj.validateVisibleOptions();
    });
});