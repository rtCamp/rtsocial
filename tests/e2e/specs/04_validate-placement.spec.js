/**
 * WordPress dependencies
 */
const { test } = require('@wordpress/e2e-test-utils-playwright');
const { RtSocial } = require('../page/RtSocial.js');
test.describe('Validate Placement and buttons functionality in the front end. ', () => {
    test('Check rtSocial placement checkbox in the backend', async ({ admin, page, editor }) => {
        const rtSocialobj = new RtSocial(page);
        await rtSocialobj.navigateToSettingPage();
        await rtSocialobj.validatePlacementCheckbox();
    });
    test(' Validate rtSocial Top placement settings with Vertical button style.', async ({ admin, page, editor }) => {
        const rtSocialobj = new RtSocial(page);
        await rtSocialobj.navigateToSettingPage();
        await rtSocialobj.setCenterAlignTopPlaceVerticalIcon();
        await rtSocialobj.saveSetting();
        await rtSocialobj.validateCenterAlignTopPlaceVerticalIcon();
    });
    test(' Validate rtSocial Bottom placement settings with Vertical button style.', async ({ admin, page, editor }) => {
        const rtSocialobj = new RtSocial(page);
        await rtSocialobj.navigateToSettingPage();
        await rtSocialobj.setCenterAlignBottomPlaceVerticalIcon();
        await rtSocialobj.saveSetting();
        await rtSocialobj.valiateCenterAlignBottomPlaceVerticalIcon();
    });

});