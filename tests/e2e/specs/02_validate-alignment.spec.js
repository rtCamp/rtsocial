/**
 * WordPress dependencies
 */
const { test, expect } = require('@wordpress/e2e-test-utils-playwright');
const { RtSocial } = require('../page/RtSocial.js');
const { selectors } = require('../utils/selectors.js');
test.describe('Validate Alignement with button functionality', () => {
    test('Check rtSocial button Alignment in the backend', async ({  page }) => {
        const rtSocialobj = new RtSocial(page);
        await rtSocialobj.navigateToSettingPage();
        await rtSocialobj.validateAlignmentCheckbox(); 
    });
    test('Validate rtSocial Left alignment with fixed position and button style with button functionality', async ({ context, page }) => {
        const rtSocialobj = new RtSocial(page);
        await rtSocialobj.navigateToSettingPage();
        await rtSocialobj.setLeftAlignTopPlaceIcon();
        await rtSocialobj.saveSetting();
        await rtSocialobj.validateLeftAlignTopPlaceIcon();
        // Validate twitter
        const [newPage] = await Promise.all([
            context.waitForEvent('page'),
            page.locator(selectors.frontEndTwitterIcon).first().click() // Opens a new tab
        ])
        await newPage.waitForLoadState();
        await expect(newPage).toHaveURL(/twitter/);
    });
    test('Validate rtSocial Right alignment with fixed position and button style', async ({ context, page }) => {
        const rtSocialobj = new RtSocial(page);
        await rtSocialobj.navigateToSettingPage();
        await rtSocialobj.setRightAlignTopPlaceIcon();
        await rtSocialobj.saveSetting();
        await rtSocialobj.validateRightAlignTopPlaceIcon();
        // Validate Facebook
        const [newpage] = await Promise.all([
            context.waitForEvent('page'),
            page.locator(selectors.frontEndFacebookIcon).first().click() // Opens a new tab // Opens a new tab
        ])
        await newpage.waitForLoadState();
        await expect(newpage).toHaveURL(/facebook/);
        // login and share
        await newpage.locator(selectors.facebookInput).fill(selectors.userGmail);
        await newpage.locator(selectors.facebookPassword).fill(selectors.userPassword);
        await newpage.locator(selectors.facbookSubmit).click();
        await expect(newpage).toHaveURL(/facebook/);
        // validate & Share
        await expect(newpage.locator(selectors.facebookShareButton)).toBeVisible();
        await newpage.locator(selectors.facebookShareButton).click();
    });
});