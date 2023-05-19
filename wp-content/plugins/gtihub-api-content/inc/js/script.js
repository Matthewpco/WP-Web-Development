// Targeting document sections and setting Github user name
const overview = document.querySelector(".overview");
const repoList = document.querySelector(".repo-list");
const reposSection = document.querySelector(".repos");
const repoDataSection = document.querySelector(".repo-data");
const backButton = document.querySelector(".git-back-button");
const filterInput = document.querySelector(".filter-repos");
const gitUserName = "Matthewpco";

// Variable set to identify where shortcode is active and run API scripts 
let scActive;


// Check to see if the class from shortcode is present and set true or false
const activateSC = () => {
	if (overview == null) {
		console.log("SC inactive");
		scActive = false;
	} else if (overview !== null) {
		console.log("SC active");
		scActive = true;
	}
}
activateSC();
// If shortcode class is present run fetch script
if (scActive == true) {
	// Fetch Github user data
	const getUserData = async function() {
    const res = await fetch(
        `https://api.github.com/users/${gitUserName}`
        );
        const uData = await res.json();
        popData(uData);
}

getUserData();

// Populate fetched user data 
let popData = (uData) => {
   let dataBody =  document.createElement("div");
   dataBody.classList.add("user-info", "github-sc-active");
   dataBody.innerHTML = `<figure>
   <img class="imgClass" alt="user avatar" src=${uData.avatar_url} />
 </figure>
 <div>
   <p><strong>Name:</strong> ${uData.name}</p>
   <p><strong>Bio:</strong> ${uData.bio}</p>
   <p><strong>Location:</strong> ${uData.location}</p>
   <p><strong>Number of public repos:</strong> ${uData.public_repos}</p>
 </div> `;
overview.append(dataBody)
getRepoData();
};


// Fetch Github repo data
const getRepoData = async function () {
  const res = await fetch(
    `https://api.github.com/users/${gitUserName}/repos?per_page=9&sort=updated`
  );

  const repoData = await res.json();
  popRepo(repoData);
};

// Populate repo data
const popRepo = (repoData) => {
  filterInput.classList.remove("hide");
  for (const repo of repoData) {
    const repoLi = document.createElement("li");
    repoLi.classList.add("repo");
    repoLi.innerHTML = `<h3 class="h3Class"> ${repo.name} </h3>`;
    repoList.append(repoLi);
  }
};


// When a repo list element is clicked that matches h3 it will fetch repo sub data
repoList.addEventListener("click", (e) => {
  if (e.target.matches("h3")) {
    const repoName = e.target.innerText;
    getRepoSubData(repoName);
  }
});


// Fetch repo sub data for event listener
const getRepoSubData = async function (repoName) {
  const res = await fetch(
    `https://api.github.com/repos/${gitUserName}/${repoName}`
  )
  const repoSubData = await res.json();
  const fetchLanguages = await fetch(repoSubData.languages_url)
  const langaugeData = await fetchLanguages.json();
  const languages = [];
  for(language in langaugeData) {
    languages.push(language)
  }
  popRepoSubData(repoSubData, languages);
};


//Display repo sub data
const popRepoSubData = (repoSubData, languages) => {
  repoDataSection.innerHTML = "";
  const repoSubDiv = document.createElement("div");
  repoSubDiv.innerHTML = `
  <h3 class="h3Class">Name: ${repoSubData.name}</h3>
    <p>Description: ${repoSubData.description}</p>
    <p>Default Branch: ${repoSubData.default_branch}</p>
    <p>Languages: ${languages.join(", ")}</p>
    <a class="visit" href="${repoSubData.html_url}" target="_blank" rel="noreferrer noopener">View Repo on GitHub!</a>
    `;
    repoDataSection.append(repoSubDiv)
    repoDataSection.classList.remove("hide")
    reposSection.classList.add("hide")
    backButton.classList.remove("hide");
};

// Event listeners

backButton.addEventListener("click", () => {
  reposSection.classList.remove("hide");
  repoDataSection.classList.add("hide");
  backButton.classList.add("hide");
});

filterInput.addEventListener("input", (e) => {
  let searchInput = e.target.value;
  const repoSection = document.querySelectorAll(".repo");
  searchInputLc = searchInput.toLowerCase();
  console.log(searchInputLc)
  for (const repoSearch of repoSection) {
    const repoValueLc = repoSearch.innerText.toLowerCase();
    if (repoValueLc.includes(searchInputLc)) {
      repoSearch.classList.remove("hide")

    }
    else {
      repoSearch.classList.add("hide")
    }
  }
});
}

else if (scActive == false) {
	console.log("SC was not found and script execution halted.");
}