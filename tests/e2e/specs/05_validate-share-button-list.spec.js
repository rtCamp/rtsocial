/**
 * WordPress dependencies
 */
const { test, expect } = require('@wordpress/e2e-test-utils-playwright');
const { RtSocial } = require('../page/RtSocial.js');
const { selectors } = require('../utils/selectors.js');
test.describe('Validate Social Sharing Handle Inputs', () => {
    test(' Validate rtSocial Handle and sharing buttons', async ({  page }) => {
        const rtSocialobj = new RtSocial(page);
        await rtSocialobj.navigateToSettingPage();
        await rtSocialobj.setDragDrop();
        await rtSocialobj.saveSetting();
        // Validate Drag and drop after performing action
        await expect(page.locator(selectors.activeButtonDiv)).toBeVisible();
    });
    test(' Validate rtSocial Twitter Handles input', async ({ page }) => {
        const rtSocialobj = new RtSocial(page);
        await rtSocialobj.navigateToSettingPage();
        await rtSocialobj.setValidateTwitterHandle();
        await rtSocialobj.saveSetting();
        await expect(page.locator(selectors.twitterHandle)).toHaveValue("test");
    });

    test(' Validate rtSocial Facebook Handles input', async ({  page }) => {
        const rtSocialobj = new RtSocial(page);
        await rtSocialobj.navigateToSettingPage();
        await rtSocialobj.setValidateFacebookHandle();
        await rtSocialobj.saveSetting();
        await expect(page.locator(selectors.facebookHandleToken)).toBeVisible();
    });

});