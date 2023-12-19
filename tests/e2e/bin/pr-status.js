#!/usr/bin/env node
// Octokit.js
// https://github.com/octokit/core.js#readme

const { Octokit } = require("@octokit/core");

const octokit = new Octokit({
    auth: process.env.TOKEN,
});

octokit.request("POST /repos/{org}/{repo}/statuses/{sha}", {
    org: "rtCamp",
    repo: "rtsocial",
    sha: process.env.SHA ? process.env.SHA : process.env.COMMIT_SHA,
    state: "success",
    conclusion: "success",
    target_url:
        "https://www.tesults.com/results/rsp/view/results/project/a1f6669e-0452-4484-a2be-b3224633bbae",
    description: "Successfully synced to Tesults",
    context: "E2E Test Result",
});