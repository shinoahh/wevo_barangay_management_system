document.addEventListener("DOMContentLoaded", function() {
    // Fetch and display all volunteers on page load
    fetchVolunteers('');

    // Add event listener for real-time search
    document.getElementById('searchInput').addEventListener('input', function() {
        let query = this.value;
        fetchVolunteers(query);
    });

    function fetchVolunteers(query) {
        console.log('Fetching volunteers with query:', query); // Debug log
        $.ajax({
            url: 'search_volunteers.php',
            type: 'GET',
            data: { search: query },
            success: function(response) {
                console.log('Response received:', response); // Debug log
                document.getElementById('volunteerTableBody').innerHTML = response;
                addVolunteerRowEventListeners();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX request failed:', textStatus, errorThrown); // Debug log
            }
        });
    }

    function addVolunteerRowEventListeners() {
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
    }

    let volunteerInformation = document.getElementById("volunteerInformation");
    let volunteerAccepted = document.getElementById("volunteerAccepted");
    let volunteerDeclined = document.getElementById("volunteerDeclined");
    let btnVolInformation = document.getElementById("btnVolInformation");
    let btnVolAccepted = document.getElementById("btnVolAccepted");
    let btnVolDeclined = document.getElementById("btnVolDeclined");

    function showSection(section) {
        volunteerInformation.classList.remove("active");
        volunteerAccepted.classList.remove("active");
        volunteerDeclined.classList.remove("active");

        section.classList.add("active");
    }

    btnVolInformation.addEventListener("click", function() {
        showSection(volunteerInformation);
    });

    btnVolAccepted.addEventListener("click", function() {
        showSection(volunteerAccepted);
    });

    btnVolDeclined.addEventListener("click", function() {
        showSection(volunteerDeclined);
    });

    showSection(volunteerInformation); // Default section to show
});
