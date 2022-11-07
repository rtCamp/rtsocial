/**
 * WordPress dependencies
 */
const { test, expect } = require('@wordpress/e2e-test-utils-playwright');
const { Placement, ButtonStyle, Alignment, SaveSetting, PlacementValidation } = require("../utils/locator.js");


test.describe('Validate button, alginment in the front end. ', () => {
    test.beforeEach(async ({ admin }) => {
        await admin.visitAdminPage('options-general.php');
    });
    test('Check rtSocial button checkbox in the backend', async ({ admin, page, editor }) => {
        await page.locator("role=link[name='rtSocial Options'i]").click();
        // Test Button Style checkboxes
        expect(ButtonStyle.Horizontal).not.toBeNull();
        expect(ButtonStyle.IconCount).not.toBeNull();
        expect(ButtonStyle.Icon).not.toBeNull();
        expect(ButtonStyle.Vertical).not.toBeNull();
    });
    test(' Validate rtSocial Horizontal Buttons and with placement and Alignment', async ({ context, page, editor }) => {
        await page.locator("role=link[name='rtSocial Options'i]").click();
        //Select Placement Top+ Alignment center
        await page.locator(Placement.Top).check();
        await page.locator(Alignment.Center).check();
        // Select button Horizontal
        await page.locator(ButtonStyle.Horizontal).check()
        // Save Changes
        await page.locator(SaveSetting.Button).click();
        expect(page.locator(SaveSetting.Message)).not.toBeNull();
        // Check changes in the front end
        await Promise.all([
            page.click("#wp-admin-bar-site-name > a"),
        ]);
        // Validate Placement By checking Position is as expected
        await page.focus(PlacementValidation.ButtonHorizontal);
        // Validate pin it
        const [newPage] = await Promise.all([
            context.waitForEvent('page'),
            page.locator('div.rtsocial-pinterest-horizontal-button > a').click() // Opens a new tab
        ])
        await newPage.waitForLoadState();
        await expect(newPage).toHaveURL(/pinterest/);
    });
    test(' Validate rtSocial Icon Buttons and with placement and Alignment', async ({ context, page, editor }) => {
        await page.locator("role=link[name='rtSocial Options'i]").click();
        //Select Placement Top+ Alignment center
        await page.locator(Placement.Top).check();
        await page.locator(Alignment.Center).check();
        // Select button Vertical
        await page.locator(ButtonStyle.Icon).check()
        // Save Changes
        await page.locator(SaveSetting.Button).click();
        expect(page.locator(SaveSetting.Message)).not.toBeNull();
        // Check changes in the front end
        await Promise.all([
            page.click("#wp-admin-bar-site-name > a"),
        ]);
        // Validate Placement By checking Position is as expected
        await page.focus(PlacementValidation.Icon);
        // Validate linkedin 
        const [newPage] = await Promise.all([
            context.waitForEvent('page'),
            page.locator('div.rtsocial-linkedin-icon > div > a').click() // Opens a new tab
        ])
        await newPage.waitForLoadState();
        await expect(newPage).toHaveURL(/link/);

    });
    test(' Validate rtSocial IconCount Buttons and with placement and Alignment.', async ({ admin, page, editor }) => {
        await page.locator("role=link[name='rtSocial Options'i]").click();
        //Select Placement Top+ Alignment center
        await page.locator(Placement.Bottom).check();
        await page.locator(Alignment.Center).check();
        // Select button Vertical
        await page.locator(ButtonStyle.IconCount).check()
        // Save Changes
        await page.locator(SaveSetting.Button).click();
        expect(page.locator(SaveSetting.Message)).not.toBeNull();
        // Check changes in the front end
        await Promise.all([
            page.click("#wp-admin-bar-site-name > a"),
        ]);
        
        // Validate Placement By checking Position is as expected
        await page.focus(PlacementValidation.IconCount)
    });

});