/**
 * WordPress dependencies
 */
const { test, expect } = require( '@wordpress/e2e-test-utils-playwright' );
const { RtSocial } = require('../page/RtSocial.js');
const { selectors } = require('../utils/selectors.js');
test.describe('Validate button, alginment in the front end. ', () => {
    test('Check rtSocial button checkbox in the backend', async ({ page }) => {
        const rtSocialobj = new RtSocial(page);
        await rtSocialobj.navigateToSettingPage();
        await rtSocialobj.validateButtonStyleCheckbox();
    });
    test(' Validate rtSocial Horizontal Buttons and with placement and Alignment', async ({ context, page }) => {
        const rtSocialobj = new RtSocial(page);
        await rtSocialobj.navigateToSettingPage();
        await rtSocialobj.setCenterAlignTopPlaceHorizontal();
        await rtSocialobj.saveSetting();
        await rtSocialobj.validateCenterAlignTopPlaceHorizontal();
        // Validate pinterest
        const [newPage] = await Promise.all([
            context.waitForEvent('page'),
            page.locator(selectors.pinterestIcon).click()
        ])
        await newPage.waitForLoadState();
        await expect(newPage).toHaveURL(/pinterest/);
    });

    test(' Validate rtSocial Icon Buttons and with placement and Alignment', async ({ context, page }) => {
        const rtSocialobj = new RtSocial(page);
        await rtSocialobj.navigateToSettingPage();
        await rtSocialobj.setCenterAlignTopPlaceIconButtonStyle();
        await rtSocialobj.saveSetting();
        await rtSocialobj.validateCenterAlignTopPlaceIconButtonStyle();
        // Validate linkedin 
        const [newPage] = await Promise.all([
            context.waitForEvent('page'),
            page.locator(selectors.linkedinIcon).click()
        ])
        await newPage.waitForLoadState();
        await expect(newPage).toHaveURL(/link/);

    });
    test(' Validate rtSocial IconCount Buttons and with placement and Alignment.', async ({ page }) => {
        const rtSocialobj = new RtSocial(page);
        await rtSocialobj.navigateToSettingPage();
        await rtSocialobj.setCenterAlignBottomPlaceIconCount();
        await rtSocialobj.saveSetting();
        await rtSocialobj.validateCenterAlignBottomPlaceIconCount();
    });
});