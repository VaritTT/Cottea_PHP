const emailInput = document.querySelector('.email');
const passwordInput = document.querySelector('.password-login');

const errorMessage = document.getElementById('error-message');

// ถ้ากรอกให้ error display none 
emailInput.addEventListener('keydown', function () { // email
    errorMessage.style.display = 'none';
});

passwordInput.addEventListener('keydown', function () { // password
    errorMessage.style.display = 'none';
});

document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);

    // รับค่า error = invalid ใน URL (ถ้ามี)
    const error = urlParams.get('error');
    // ถ้ารับค่า error มาได้สำเร็จ
    if (error === 'invalid') {
        const errorMessage = document.getElementById('error-message'); //รับข้อความ error มา
        errorMessage.style.display = 'flex';
        errorMessage.style.marginBottom = '24px';
        errorMessage.style.justifyContent = 'start';
        errorMessage.style.alignItems = 'center';
        errorMessage.style.color = 'red';
    }

    // ฟังก์ชันเปิดปิด (allow function) 
    const togglePasswordVisibility = (eyeIcon) => {
        const input = eyeIcon.closest('.input-group').querySelector('input'); // Adjusted to find input related to the clicked icon

        // ตรวจ type ของมัน
        if (input.type === 'password') {
            eyeIcon.classList.replace('bx-show', 'bx-hide');
            input.type = 'text';
        } else {
            eyeIcon.classList.replace('bx-hide', 'bx-show');
            input.type = 'password';
        }
    };

    // เมื่อเราทำการคลิกที่คลาส เปิด-ปิดตา
    document.querySelectorAll('.bx-hide').forEach(eyeIcon => {
        // เมื่อมีการคลิก
        eyeIcon.addEventListener('click', function () {
            togglePasswordVisibility(this);
        });
    });
});

