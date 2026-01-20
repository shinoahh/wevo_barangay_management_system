window.onload = function() {
    console.log("hello");

    document.querySelectorAll(".volunteer-row").forEach(function(row) {
        row.addEventListener("click", function() {
            let id = this.getAttribute("data-id");
            let name = this.getAttribute("data-name");
            let middleName = this.getAttribute("data-middle-name");
            let surname = this.getAttribute("data-surname");
            let age = this.getAttribute("data-age");
            let gender = this.getAttribute("data-gender");
            let phone = this.getAttribute("data-phone");
            let email = this.getAttribute("data-email");
            let birthday = this.getAttribute("data-birthday");
            let address = this.getAttribute("data-address");
            let image = this.getAttribute("data-image");

            console.log({ id, name, middleName, surname, age, gender, phone, email, birthday, address, image });

            document.getElementById("volunteerId").innerText = id;
            document.getElementById("volunteerName").innerText = name;
            document.getElementById("volunteerMiddleName").innerText = middleName;
            document.getElementById("volunteerSurname").innerText = surname;
            document.getElementById("volunteerAge").innerText = age;
            document.getElementById("volunteerGender").innerText = gender;
            document.getElementById("volunteerPhone").innerText = phone;
            document.getElementById("volunteerEmail").innerText = email;
            document.getElementById("volunteerBirthday").innerText = birthday;
            document.getElementById("volunteerAddress").innerText = address;
            document.getElementById("volunteerImage").src = image;

            document.getElementById("floatingForm").style.display = "block";
        });
    });

    document.querySelector(".close-btn").addEventListener("click", function() {
        document.getElementById("floatingForm").style.display = "none";
    });
};
