/**
 * WordPress dependencies
 */
const { test, expect } = require('@wordpress/e2e-test-utils-playwright');
const { Placement, ButtonStyle, Alignment, SaveSetting, PlacementValidation } = require("../utils/locator.js");


test.describe('Validate Placement and buttons functionality in the front end. ', () => {
    test.beforeEach(async ({ admin }) => {
        await admin.visitAdminPage('options-general.php');
    });
    test('Check rtSocial placement checkbox in the backend', async ({ admin, page, editor }) => {
        await page.locator("role=link[name='rtSocial Options'i]").click();
        // Test Placement checkboxes
        expect(Placement.Top).not.toBeNull();
        expect(Placement.Bottom).not.toBeNull();
    });
    test(' Validate rtSocial Top placement settings with Vertical button style.', async ({ admin, page, editor }) => {
        await page.locator("role=link[name='rtSocial Options'i]").click();
        //Select Placement Top+ Alignment center
        await page.locator(Placement.Top).check();
        await page.locator(Alignment.Center).check();
        // Select button Vertical
        await page.locator(ButtonStyle.Vertical).check()
        // Save Changes
        await page.locator(SaveSetting.Button).click();
        expect(page.locator(SaveSetting.Message)).not.toBeNull();
        // Check changes in the front end
        await Promise.all([
            page.click("#wp-admin-bar-site-name > a"),
        ]);
        // Validate Placement By checking Position is as expected
        await page.focus(PlacementValidation.Top);
    });
    test(' Validate rtSocial Bottom placement settings with Vertical button style.', async ({ admin, page, editor }) => {
        await page.locator("role=link[name='rtSocial Options'i]").click();
        //Select Placement bottom+ Alignment center
        await page.locator(Placement.Bottom).check();
        await page.locator(Alignment.Center).check();
        // Select button Vertical
        await page.locator(ButtonStyle.Vertical).check()
        // Save Changes
        await page.locator(SaveSetting.Button).click();
        expect(page.locator(SaveSetting.Message)).not.toBeNull();
        // Check changes in the front end
        await Promise.all([
            page.click("#wp-admin-bar-site-name > a"),
        ]);
        // Validate Placement By checking Position is as expected
        await page.focus(PlacementValidation.Bottom)
    });

});