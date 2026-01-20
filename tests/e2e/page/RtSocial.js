const { expect } = require("@playwright/test");
const { selectors } = require('../utils/selectors')
exports.RtSocial = class RtSocial {
    constructor(page,context) {
        this.page = page;
        this.context= context
    }
    // this functions is to navigate to the Mention link Setting page
    async navigateToSettingPage() {
        await this.page.goto("./wp-admin/options-general.php?page=rtsocial-options", { waitUntil: "load" });
    }
    // this function is used to validate all the visible options in the settings page
    async validateVisibleOptions(){
        const placementSettings = this.page.locator(selectors.placementSettingsDiv)
        expect(placementSettings).not.toBeNull();
        const buttonStyle = this.page.locator(selectors.buttonStyleDiv)
        expect(buttonStyle).not.toBeNull();
        const alignmentSetting = this.page.locator(selectors.leftAlignment);
        expect(alignmentSetting).not.toBeNull();
        const activeButtons = this.page.locator(selectors.activeButtonDiv);
        expect(activeButtons).not.toBeNull();
        const twitterHandle = this.page.locator(selectors.twitterHandleInput);
        expect(twitterHandle).not.toBeNull();
        const facebookToken = this.page.locator(selectors.faceBooktokenInput);
        expect(facebookToken).not.toBeNull();
    }
    // this function is used to test Alignment Settings checkboxes
    async validateAlignmentCheckbox(){
        expect(selectors.leftAlignment).not.toBeNull()
        expect(selectors.rightAlignment).not.toBeNull()
        expect(selectors.centerAlignment).not.toBeNull()
        expect(selectors.noneAlignment).not.toBeNull()
    }
    // this function is used to Test Button Style checkboxes
    async validateButtonStyleCheckbox(){
        expect(selectors.horizontalButtonStyle).not.toBeNull();
        expect(selectors.iconCountButtonStyle).not.toBeNull();
        expect(selectors.iconButtonStyle).not.toBeNull();
        expect(selectors.verticalButtonStyle).not.toBeNull();
    }
    // this function is used to Test Placement Checkboxes
    async validatePlacementCheckbox(){
        expect(selectors.topPlacement).not.toBeNull();
        expect(selectors.bottomPlacement).not.toBeNull();
    }

    // this function is used to set Left aligned in top position with icon style Social Icons
    async setLeftAlignTopPlaceIcon(){ 
        await this.page.locator(selectors.topPlacement).check();
        await this.page.locator(selectors.iconButtonStyle).check()
        await this.page.locator(selectors.leftAlignment).check();
    }
    // this functions is used to save settings
    async saveSetting(){
        await this.page.locator(selectors.buttonSaveSetting).click();
        expect(this.page.locator(selectors.messageSaveSetting)).not.toBeNull();
    }
    // this function is used to validate Left aligned in top position with iconCount style Social Icons
    async validateLeftAlignTopPlaceIcon(){
        await Promise.all([
            this.page.click(selectors.adminBar),
        ]);
        await expect(this.page.locator(selectors.leftAlignedValidate).first()).toBeVisible();
    }
    // this function is used to set Right aligned in top position with iconCount style Social Icons
    async setRightAlignTopPlaceIcon(){
        await this.page.locator(selectors.topPlacement).check();
        await this.page.locator(selectors.iconCountButtonStyle).check()
        await this.page.locator(selectors.rightAlignment).check();
    }
    // this function is used to validate Right aligned in top position with iconCount style Social Icons
    async validateRightAlignTopPlaceIcon(){
        await Promise.all([
            this.page.click(selectors.adminBar),
        ]);
        await expect(this.page.locator(selectors.rightAlignedValidate).first()).toBeVisible();
    }
    // this function is used to set Center alignement with top placement and horizontal
    async setCenterAlignTopPlaceHorizontal(){
        await this.page.locator(selectors.topPlacement).check();
        await this.page.locator(selectors.centerAlignment).check();
        await this.page.locator(selectors.horizontalButtonStyle).check()
    }
    // this function is used to validate Center alignement with top placement and horizontal
    async validateCenterAlignTopPlaceHorizontal(){
        await Promise.all([
            this.page.click(selectors.adminBar),
        ]);
        await expect(this.page.locator(selectors.buttonHorizontalValidate).first()).toBeVisible();
    }
     // this function is used to set Center alignement with top placement and icon button style
    async setCenterAlignTopPlaceIconButtonStyle() {
        await this.page.locator(selectors.topPlacement).check();
        await this.page.locator(selectors.centerAlignment).check();
        await this.page.locator(selectors.iconButtonStyle).check()
    }
    // this function is used to validate Center alignement with top placement and icon button style
    async validateCenterAlignTopPlaceIconButtonStyle(){
        await Promise.all([
            this.page.click(selectors.adminBar),
        ]);
        await expect(this.page.locator(selectors.iconValidate).first()).toBeVisible();
    }
    // this function is used to set Center alignement with botton placement and iconcount button style
    async setCenterAlignBottomPlaceIconCount(){
        await this.page.locator(selectors.bottomPlacement).check();
        await this.page.locator(selectors.centerAlignment).check();
        await this.page.locator(selectors.iconCountButtonStyle).check()
    }
     // this function is used to validate Center alignement with botton placement and iconcount button style
    async validateCenterAlignBottomPlaceIconCount() {
        await Promise.all([
            this.page.click(selectors.adminBar),
        ]);
        await expect(this.page.locator(selectors.iconCountValidate).first()).toBeVisible();
    }
    // this function is used to set Center alignement with top placement and vertical button style
    async setCenterAlignTopPlaceVerticalIcon(){
        await this.page.locator(selectors.topPlacement).check();
        await this.page.locator(selectors.centerAlignment).check();
        await this.page.locator(selectors.verticalButtonStyle).check()
    }
    // this function is used to Validate Center alignement with top placement and vertical button style
    async validateCenterAlignTopPlaceVerticalIcon(){
        await Promise.all([
            this.page.click(selectors.adminBar),
        ]);
        await expect(this.page.locator(selectors.topValidate).first()).toBeVisible();
    }
    // this function is used to set Center alignement with bottom placement and vertical button style
    async setCenterAlignBottomPlaceVerticalIcon(){
        await this.page.locator(selectors.bottomPlacement).check();
        await this.page.locator(selectors.centerAlignment).check();
        await this.page.locator(selectors.verticalButtonStyle).check();
    }
     // this function is used to validate Center alignement with bottom placement and vertical button style
    async valiateCenterAlignBottomPlaceVerticalIcon(){
        await Promise.all([
            this.page.click(selectors.adminBar),
        ]);
        await expect(this.page.locator(selectors.bottomValidate).first()).toBeVisible();
    }
    // this function is used to Check active and inactive drag and drop
    async setDragDrop(){
        const src = await this.page.$(selectors.activeButtonDiv)
        const dst = await this.page.$(selectors.inactiveDestButton);
        if (src && dst) {
            const srcBound = await src.boundingBox()
            const dstBound = await dst.boundingBox()
            if (srcBound && dstBound) {
                await this.page.mouse.click(srcBound.x + srcBound.width, srcBound.y + srcBound.height)
                await this.page.mouse.down()
                await this.page.mouse.move(srcBound.x + srcBound.width, srcBound.y + srcBound.height)
                await this.page.mouse.move(dstBound.x + dstBound.width, dstBound.y + dstBound.height)
                await this.page.mouse.down();
            } else {
                throw new Error("No Element")
            }
        }
    }
    // this function is used to set and validate twitter handle input
    async setValidateTwitterHandle(){
        await this.page.locator(selectors.twitterHandle).fill("test");
        await this.page.locator(selectors.twitterRaltedHandle).fill("test");   
    }
    // this function is used to set and validate facebook token input backend
    async setValidateFacebookHandle(){
        await this.page.locator(selectors.facebookHandleToken).fill(selectors.facebookTokenValue);
        await this.page.locator(selectors.facbookHandleCheckbox).nth(3).check();
    }
}