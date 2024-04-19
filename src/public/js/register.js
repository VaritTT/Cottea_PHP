
const form = document.querySelector("form"),
    emailField = form.querySelector(".email-field"),
    emailInput = emailField.querySelector(".email");

const errorAlready = document.querySelector(".email-already")
if (errorAlready) { // ถ้า errorAlready ไม่เป็น null หรือ undefined
    emailField.addEventListener("keydown", (event) => {
        errorAlready.style.display = 'none'; // ตั้งเป็น none ถ้ากำลังกรอก
    });
}

// ฟังก์ชัน check email
function checkEmail() {
    const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
    if (!emailInput.value.match(emailPattern)) {
        emailField.classList.add("invalid");
        return false; // ถ้าไม่ตรงตาม Pattern เพิ่ม invalid ลงในชื่อ Class
    } else {
        emailField.classList.remove("invalid");
        return true; // ถ้าตรงตาม Pattern ลบ invalid ออก
    }
}
// Calling Function on Form Submit
form.addEventListener("submit", (e) => {
    // Preventing form from submitting
    e.preventDefault(); //หยุดตรวจก่อนส่งฟอร์มไปต่อ
    // Check email validation
    if (checkEmail()) {
        // If email is valid, remove preventDefault to allow form submission
        e.target.submit();
    }
});

// กำหนดว่าจะให้เช็คตอนอยู่สถานะไหนบ้าง
emailInput.addEventListener("keyup", checkEmail);
emailInput.addEventListener("focus", checkEmail);
emailInput.addEventListener("input", checkEmail);

// ปุ่มยอมรับข้อตกลง
document.getElementById('termsCheckbox').addEventListener('change', function () {
    document.getElementById('submitButton').disabled = !this.checked;
});