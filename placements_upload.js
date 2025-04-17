document.addEventListener("DOMContentLoaded", function () {
    // Toggle the display of the placement_requirements div
    document.querySelector(".btn.btn-primary").addEventListener("click", function () {
        let requirementsDiv = document.querySelector(".placement_requirements");
        requirementsDiv.style.display = (requirementsDiv.style.display === "none" || requirementsDiv.style.display === "") ? "block" : "none";
    });

    // AJAX submission for the "Post exam for required skills" form
    document.getElementById("questions").addEventListener("submit", function (e) {
        e.preventDefault(); // Prevent default form submission

        var formData = new FormData(this); // 'this' refers to the form
        formData.append('companyName', document.querySelector("input[name='companyName'][type='text']").value); // Dynamically set companyName
        formData.append('requirementSkill', document.querySelector("input[name='requirementSkill'][type='text']").value); // Dynamically set companyName
        fetch('set_req_qp_process.php', {
            method: 'post',
            body: formData
        }).then(function (response) {
            return response.text();
        }).then(function (text) {
            console.log(text); // Handle response
            alert("Exam posted successfully!");
        }).catch(function (error) {
            console.error(error); // Handle errors
        });
    });

    // Function to dynamically add questions
    window.addQuestion = function () {
        let questionsContainer = document.getElementById('questions-container');
        let questionContainer = document.createElement('div');
        questionContainer.className = 'question-container';
        questionContainer.innerHTML = `
            <label for="question">Question</label>
            <input type="text" name="question[]" class="question">
            <input type="text" name="option1[]" placeholder="Enter option 1" class="option">
            <input type="text" name="option2[]" placeholder="Enter option 2" class="option">
            <input type="text" name="option3[]" placeholder="Enter option 3" class="option">
            <input type="text" name="option4[]" placeholder="Enter option 4" class="option">
            <input type="text" name="option5[]" placeholder="Enter option 5" class="option">
            <input type="text" name="correct[]" placeholder="Enter correct option" class="correct">
        `;
        questionsContainer.appendChild(questionContainer);
    };
});
