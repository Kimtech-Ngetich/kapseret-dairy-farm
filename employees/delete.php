</div>

</div>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const form = document.querySelector("form");

    const employeeNumber = document.querySelector("[name='employee_number']");
    const fullName = document.querySelector("[name='full_name']");
    const gender = document.querySelector("[name='gender']");
    const phone = document.querySelector("[name='phone']");
    const email = document.querySelector("[name='email']");
    const department = document.querySelector("[name='department']");
    const position = document.querySelector("[name='position']");
    const salary = document.querySelector("[name='salary']");

    form.addEventListener("submit", function(e){

        if(employeeNumber.value.trim() === "")
        {
            alert("Employee number is required.");
            employeeNumber.focus();
            e.preventDefault();
            return;
        }

        if(fullName.value.trim() === "")
        {
            alert("Please enter the employee's full name.");
            fullName.focus();
            e.preventDefault();
            return;
        }

        if(gender.value === "")
        {
            alert("Please select gender.");
            gender.focus();
            e.preventDefault();
            return;
        }

        if(email.value !== "")
        {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if(!emailPattern.test(email.value))
            {
                alert("Please enter a valid email address.");
                email.focus();
                e.preventDefault();
                return;
            }
        }

        if(phone.value !== "" && phone.value.length < 10)
        {
            alert("Please enter a valid phone number.");
            phone.focus();
            e.preventDefault();
            return;
        }

        if(department.value.trim() === "")
        {
            alert("Department is required.");
            department.focus();
            e.preventDefault();
            return;
        }

        if(position.value.trim() === "")
        {
            alert("Position is required.");
            position.focus();
            e.preventDefault();
            return;
        }

        if(salary.value !== "")
        {
            if(parseFloat(salary.value) < 0)
            {
                alert("Salary cannot be negative.");
                salary.focus();
                e.preventDefault();
                return;
            }
        }

    });

});

</script>

<?php include "../includes/footer.php"; ?>

</body>
</html>