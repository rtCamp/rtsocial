/**
 * WordPress dependencies
 */
const { test, expect } = require('@wordpress/e2e-test-utils-playwright');
const { Placement, ButtonStyle, Alignment, SaveSetting, PlacementValidation } = require("../utils/locator.js");
test.describe('Validate Alignement with button functionality', () => {
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
    test('Validate rtSocial Left alignment with fixed position and button style with button functionality', async ({ context, page, editor }) => {
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
            page.locator('div.rtsocial-twitter-icon > div > a').first().click() // Opens a new tab
        ])
        await newPage.waitForLoadState();
        await expect(newPage).toHaveURL(/twitter/);
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
        const [newpage] = await Promise.all([
            context.waitForEvent('page'),
            page.locator('div.rtsocial-fb-icon-button > a').first().click() // Opens a new tab // Opens a new tab
        ])
        await newpage.waitForLoadState();
        await expect(newpage).toHaveURL(/facebook/);
        // login and share
        await newpage.locator("input[name='email']").fill("qartcamp@gmail.com");
        await newpage.locator("input[name='pass']").fill("xzBykf8zJrkeZV8S");
        await newpage.locator("input[name='login']").click();
        await expect(newpage).toHaveURL(/facebook/);
        await newpage.pause();
        await newpage.locator("button[name='__CONFIRM__']").click();
    });

});