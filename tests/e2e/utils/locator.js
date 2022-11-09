

const Placement = {
    Top: "input[value='top']",
    Bottom: "input[value='bottom']",
};

const ButtonStyle ={
    Vertical: "input[id='display_vertical_input']",
    Horizontal: "input[id='display_horizontal_input']",
    Icon: "input[id='display_icon_input']",
    IconCount: "input[id='display_icon_count_input']"
}

const Alignment ={
    Left: "input[id='align_left_check']",
    Center: "input[id='align_center_check']",
    Right: "input[id='align_right_check']",
    None:"input[id='align_none_check']"
}

const SaveSetting ={
    Button:"role=button[name='Save Changes'i]",
    Message:"#setting-error-settings_updated",
}

const PlacementValidation ={
    Top:"div[class='rtsocial-container rtsocial-container-align-center rtsocial-vertical']",
    Bottom:"div[class='rtsocial-container rtsocial-container-align-center rtsocial-vertical']",
    ButtonHorizontal:"div[class='rtsocial-container rtsocial-container-align-center rtsocial-horizontal']",
    Icon:"div[class='rtsocial-container rtsocial-container-align-center rtsocial-icon']",
    IconCount:"div[class='rtsocial-container rtsocial-container-align-center rtsocial-icon-count']",
    LeftAligned:"div[class='rtsocial-container rtsocial-container-align-left rtsocial-icon-count']",
    RightAligned:"div[class='rtsocial-container rtsocial-container-align-right rtsocial-icon-count']",
}


module.exports = { Placement, ButtonStyle, Alignment, SaveSetting,PlacementValidation };