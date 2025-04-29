import requests
import time
import os
import subprocess
import sys

with open("/tmp/supervisor_env_dump.txt", "w") as f:
    for k, v in os.environ.items():
        f.write(f"{k}={v}\n")

sys.stdout.flush()


# Tokens and configuration
GITHUB_TOKEN = "***REMOVED***"
SLACK_USER_ID = "<@U089NKWDGUC>"
SLACK_WEBHOOK_URL = "***REMOVED***"
REPO_PATH = "/home/trainerworkout/dev.trainer-workout.com"
REPO_OWNER = "luisczul"
REPO_NAME = "trainerworkout_v3"
LAST_COMMIT_FILE = "last_commit.txt"
VERBOSE = True

def log_debug(message):
    if VERBOSE:
        print(message)
def update_production_branch(commit_sha):
    url = f"https://api.github.com/repos/{REPO_OWNER}/{REPO_NAME}/git/refs/heads/production"
    headers = {
        "Authorization": f"token {GITHUB_TOKEN}",
        "Accept": "application/vnd.github.v3+json"
    }
    data = {
        "sha": commit_sha,
        "force": True
    }
    response = requests.patch(url, headers=headers, json=data)
    log_debug(f"🌐 Updated production branch to {commit_sha}")
    response.raise_for_status()
    
def send_slack_message(message):
    try:
        payload = { "text": message }
        response = requests.post(SLACK_WEBHOOK_URL, json=payload)
        response.raise_for_status()
        log_debug("✅ Slack message sent.")
    except requests.RequestException as e:
        log_debug(f"❌ Slack error: {e}")

def execute_git_pull():
    try:
        result = subprocess.run(["git", "-C", REPO_PATH, "pull"], capture_output=True, text=True)
        
        log_debug(f"🔁 Git pull stdout:\n{result.stdout}")

        if result.returncode == 0:
            if result.stderr.strip():
                log_debug(f"ℹ️ Git pull notes:\n{result.stderr}")
            return True
        else:
            log_debug(f"❌ Git pull failed with errors:\n{result.stderr}")
            return False

    except Exception as e:
        log_debug(f"❌ Git pull subprocess error: {e}")
        return False

def get_default_branch():
    url = f"https://api.github.com/repos/{REPO_OWNER}/{REPO_NAME}"
    headers = {
        "Authorization": f"token {GITHUB_TOKEN}",
        "Accept": "application/vnd.github.v3+json"
    }
    response = requests.get(url, headers=headers)
    response.raise_for_status()
    return response.json()["default_branch"]

def get_latest_commit(branch):
    url = f"https://api.github.com/repos/{REPO_OWNER}/{REPO_NAME}/commits/{branch}"
    headers = {
        "Authorization": f"token {GITHUB_TOKEN}",
        "Accept": "application/vnd.github.v3+json"
    }
    response = requests.get(url, headers=headers)
    response.raise_for_status()
    return response.json()

def save_last_commit_sha(sha):
    with open(LAST_COMMIT_FILE, "w") as f:
        f.write(sha)
    log_debug(f"💾 Saved SHA: {sha}")

def load_last_commit_sha():
    if os.path.exists(LAST_COMMIT_FILE):
        with open(LAST_COMMIT_FILE, "r") as f:
            sha = f.read().strip()
            log_debug(f"📦 Loaded SHA: {sha}")
            return sha
    return None

def main():
    while True:
        try:
            default_branch = get_default_branch()
            commit = get_latest_commit(default_branch)
            latest_sha = commit["sha"]
            commit_message = commit["commit"]["message"]

            last_sha = load_last_commit_sha()

            if latest_sha != last_sha:
                log_debug(f"🆕 New commit: {latest_sha}")
                if "release_prod" in commit_message:
                    update_production_branch(latest_sha)

                    if execute_git_pull():
                        send_slack_message(f"{SLACK_USER_ID} 🚀 Released commit `{latest_sha}` to production:\n> {commit_message}")
                    else:
                        send_slack_message(f"{SLACK_USER_ID} ❌ Git pull failed after updating production branch for commit `{latest_sha}`")
                else:
                    log_debug("🔍 Skipping commit without 'release_prod'")

                save_last_commit_sha(latest_sha)
            else:
                log_debug("🔄 No new commits.")

        except Exception as e:
            log_debug(f"❌ Error: {e}")

        time.sleep(10)
if __name__ == "__main__":
    main()
