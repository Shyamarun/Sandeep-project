const projectsContainer = document.getElementById("projects-container");
let commentModal;
let existingCommentsModal;


function closeCommentModal() {
    commentModal.hide();
}

function displayProjects() {
    projectsContainer.innerHTML = "";

    if (projects.length === 0) {
        console.log("No projects to display");
        return;
    }

    projects.forEach((project, index) => {
        const projectCard = document.createElement("div");
        projectCard.classList.add("col-lg-4", "col-md-6", "mb-4");
        projectCard.innerHTML = `
                    <div class="card" id='projects-container-projects'>
                        <div class="card-body">
                            <h5 class="card-title">${project.username}'s Project</h5>
                            <p class="card-text">${project.description}</p>
                            ${getFileDisplay(project.file_path)}
                            <button class="btn btn-primary" onclick="openCommentPage(${index})">Add Comment</button>
                        </div>
                    </div>
                `;
        projectsContainer.appendChild(projectCard);
    });

    commentModal = new bootstrap.Modal(document.getElementById('commentModal'));
    existingCommentsModal = new bootstrap.Modal(document.getElementById('existingCommentsModal'));

    console.log("Projects:", projects);
}

function openCommentPage(index) {
    const selectedProject = projects[index];
    document.getElementById('commentProjectId').value = selectedProject.id;
    commentModal.show();
    displayComments(selectedProject.id);
}

function getFileDisplay(filePath) {
    const fileExtension = filePath.split('.').pop().toLowerCase();
    if (fileExtension === 'pdf') {
        return `<embed src="${filePath}" type="application/pdf" width="100%" height="140px" />`;
    } else if (fileExtension === 'jpg' || fileExtension === 'jpeg' || fileExtension === 'png') {
        return `<img src="${filePath}" alt="Project Image" class="img-fluid">`;
    } else if (fileExtension === 'mp4' || fileExtension === 'webm' || fileExtension === 'ogg') {
        return `
                    <video controls class="img-fluid">
                        <source src="${filePath}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>`;
    } else {
        return `<p>Unsupported file type</p>`;
    }
}

displayProjects();
