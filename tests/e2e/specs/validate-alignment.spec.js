/**
 * WordPress dependencies
 */
const { test, expect } = require('@wordpress/e2e-test-utils-playwright');
const { Placement, ButtonStyle, Alignment, SaveSetting, PlacementValidation } = require("../utils/locator.js");


test.describe('Validate alginment is working with placement and buttons in the front end. ', () => {
    test.beforeEach(async ({ admin }) => {
        await admin.visitAdminPage('options-general.php');
    });
    test('Check rtSocial button Alignment in the backend', async ({ admin, page, editor }) => {
        await page.locator("role=link[name='rtSocial Options'i]").click();
        // Test Alignment Settings checkboxes
        expect(Alignment.Center).not.toBeNull()
        expect(Alignment.Left).not.toBeNull()
        expect(Alignment.None).not.toBeNull()
        expect(Alignment.Right).not.toBeNull()
    });
    test('Validate rtSocial Left alignment with fixed position and button style', async ({ context, page, editor }) => {
        await page.locator("role=link[name='rtSocial Options'i]").click();
        //Select Placement Top+ Button Icon count 
        await page.locator(Placement.Top).check();
        await page.locator(ButtonStyle.IconCount).check()
        // Select Alignment Left
        await page.locator(Alignment.Left).check();
        // Save Changes
        await page.locator(SaveSetting.Button).click();
        expect(page.locator(SaveSetting.Message)).not.toBeNull();
        // Check changes in the front end
        await Promise.all([
            page.click("#wp-admin-bar-site-name > a"),
        ]);
        // Validate Placement By checking Position is as expected
        await page.focus(PlacementValidation.LeftAligned);
        // Validate twitter
        const [newPage] = await Promise.all([
            context.waitForEvent('page'),
            page.locator('div.rtsocial-twitter-icon > div > a').click() // Opens a new tab
        ])
        await newPage.waitForLoadState();
        await expect(newPage).toHaveURL(/test/);
    });
    test('Validate rtSocial Right alignment with fixed position and button style', async ({ context, page }) => {
        await page.locator("role=link[name='rtSocial Options'i]").click();
        //Select Placement Top+ Button Icon count 
        await page.locator(Placement.Top).check();
        await page.locator(ButtonStyle.IconCount).check()
        // Select Alignment Right
        await page.locator(Alignment.Right).check();
        // Save Changes
        await page.locator(SaveSetting.Button).click();
        expect(page.locator(SaveSetting.Message)).not.toBeNull();
        // Check changes in the front end
        await Promise.all([
            page.click("#wp-admin-bar-site-name > a"),
        ]);
        // Validate Placement By checking Position is as expected
        await page.focus(PlacementValidation.RightAligned);

        // Validate Facebook
        const [newPage] = await Promise.all([
            context.waitForEvent('page'),
            page.locator('div.rtsocial-fb-icon-button > a').click() // Opens a new tab
        ])
        await newPage.waitForLoadState();
        await expect(newPage).toHaveURL(/facebook/);

    });

});