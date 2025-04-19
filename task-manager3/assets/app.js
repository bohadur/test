async function login() {
  const username = document.getElementById("login").value;
  const password = document.getElementById("password").value;
  const res = await fetch("../api/login.php", {
    method: "POST",
    body: JSON.stringify({ username, password })
  });
  if (res.ok) {
    document.getElementById("auth").style.display = "none";
    document.getElementById("app").style.display = "block";
    loadTasks();
  } else {
    alert("Ошибка входа");
  }
}

async function register() {
  const username = document.getElementById("login").value;
  const password = document.getElementById("password").value;
  const res = await fetch("../api/register.php", {
    method: "POST",
    body: JSON.stringify({ username, password })
  });
  if (res.ok) {
    alert("Зарегистрирован! Теперь войдите.");
  } else {
    alert("Ошибка регистрации");
  }
}

async function logout() {
  await fetch("../api/logout.php");
  document.getElementById("auth").style.display = "block";
  document.getElementById("app").style.display = "none";
}

document.getElementById("task-form").addEventListener("submit", async e => {
  e.preventDefault();
  const data = {
    title: document.getElementById("title").value,
    description: document.getElementById("description").value,
    category_id: document.getElementById("category_id").value,
    priority: document.getElementById("priority").value,
    deadline: document.getElementById("deadline").value
  };
  await fetch("../api/add_task.php", {
    method: "POST",
    body: JSON.stringify(data)
  });
  loadTasks();
});

async function loadTasks() {
  const res = await fetch("../api/get_tasks.php");
  const tasks = await res.json();
  const list = document.getElementById("tasks");
  list.innerHTML = "";
  const today = new Date().toISOString().split("T")[0];
  tasks.forEach(t => {
    const div = document.createElement("div");
    div.className = `task ${t.priority}` + (t.deadline < today && t.is_done == 0 ? " overdue" : "");
    div.innerHTML = `
      <strong>${t.title}</strong> [${t.priority}]<br>
      Категория: ${t.category}<br>
      Срок: ${t.deadline || '—'}<br>
      ${t.description}<br>
    `;
    list.appendChild(div);
  });
}