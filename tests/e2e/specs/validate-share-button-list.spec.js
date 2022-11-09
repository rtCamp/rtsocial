/**
 * WordPress dependencies
 */
const { test, expect } = require('@wordpress/e2e-test-utils-playwright');
const { SaveSetting } = require("../utils/locator.js");


test.describe('Validate Social Sharing Handle Inputs', () => {
    test.beforeEach(async ({ admin }) => {
        await admin.visitAdminPage('options-general.php');
    });
    test(' Validate rtSocial Handle and sharing buttons', async ({ admin, page, editor }) => {
        await page.locator("role=link[name='rtSocial Options'i]").click();
        // Check active and inactive drag and drop
        const src = await page.$("#rtsocial-ord-fb")
        const dst = await page.$("#rtsocial-sorter-inactive");
        if (src && dst) {
            const srcBound = await src.boundingBox()
            const dstBound = await dst.boundingBox()
            if (srcBound && dstBound) {
                await page.mouse.click(srcBound.x + srcBound.width, srcBound.y + srcBound.height)
                await page.mouse.down()
                await page.mouse.move(srcBound.x + srcBound.width , srcBound.y + srcBound.height )
                await page.mouse.move(dstBound.x + dstBound.width , dstBound.y + dstBound.height )
                await page.mouse.down();
            } else {
                throw new Error("No Element")
            }
        }
        // Save Changes
        await page.locator(SaveSetting.Button).click();
    });
    test(' Validate rtSocial Twitter Handles input', async ({ admin, page, editor }) => {
        await page.locator("role=link[name='rtSocial Options'i]").click();
        // Check input handle
        await page.locator("input[name='rtsocial_plugin_options[tw_handle]']").fill("test");
        // Check related handle
        await page.locator("input[name='rtsocial_plugin_options[tw_related_handle]']").fill("test");
        // Save changes
        await page.locator(SaveSetting.Button).click();
    });

    test(' Validate rtSocial Facebook Handles input', async ({ admin, page, editor }) => {
        await page.locator("role=link[name='rtSocial Options'i]").click();
        // Check input handle
        await page.locator("input[name='rtsocial_plugin_options[fb_access_token]']").fill("110099221");

        // Choose one button
        await page.locator("input[name='rtsocial_plugin_options[fb_style]']").nth(3).check();
        // Save changes
        await page.locator(SaveSetting.Button).click();
    });

    
});