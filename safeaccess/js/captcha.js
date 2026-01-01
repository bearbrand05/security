let captcha = "";

function generateCaptcha() {
    const chars = "ABCDEFG123456789";
    captcha = "";
    for (let i = 0; i < 5; i++) {
        captcha += chars[Math.floor(Math.random() * chars.length)];
    }
    document.getElementById("captchaText").innerText = captcha;
    document.getElementById("captchaBox").style.display = "block";
}
