const apiOffre = "../backend/api/GestionOffre.php";
const apiDomaine = "../backend/api/GestionDomaine.php";

const $ = (id) => document.getElementById(id);

const state = {
  offres: [],
  domaines: [],
  tab: "offres",
  qOffre: "",
  fDomaine: "",
  sort: "id-desc",
  qDomaine: "",
  editOffre: null,
  editDomaine: null,
};

const esc = (value) =>
  String(value ?? "").replace(/[&<>"']/g, (c) => ({
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': "&quot;",
    "'": "&#39;",
  })[c]);

const money = (n) => new Intl.NumberFormat("fr-FR").format(Number(n) || 0);

async function api(url, { method = "GET", body } = {}) {
  const res = await fetch(url, {
    method,
    headers: body ? { "Content-Type": "application/json" } : undefined,
    body: body ? JSON.stringify(body) : undefined,
  });

  const text = await res.text();
  const data = text ? JSON.parse(text) : null;

  if (!res.ok) throw new Error(data?.message || `Erreur ${res.status}`);
  return data;
}

function toast(message, type = "success") {
  const colors =
    type === "success"
      ? "border-emerald-200 bg-emerald-50 text-emerald-700"
      : "border-rose-200 bg-rose-50 text-rose-700";

  const el = document.createElement("div");
  el.className = `pointer-events-auto animate-slideIn rounded-xl border ${colors} px-4 py-3 text-sm font-medium shadow-soft`;
  el.textContent = message;
  $("toasts").appendChild(el);

  setTimeout(() => {
    el.style.transition = "opacity .3s, transform .3s";
    el.style.opacity = "0";
    el.style.transform = "translateX(24px)";
    setTimeout(() => el.remove(), 320);
  }, 3200);
}

function openModal(name) {
  $(`${name}Modal`).classList.remove("hidden");
  document.body.style.overflow = "hidden";
}

function closeModal(name) {
  const modal = $(`${name}Modal`);
  if (!modal || modal.classList.contains("hidden")) return;
  modal.classList.add("hidden");

  const anyOpen = [...document.querySelectorAll("[id$='Modal']")].some(
    (m) => !m.classList.contains("hidden")
  );
  if (!anyOpen) document.body.style.overflow = "";
}

document.addEventListener("click", (e) => {
  const closer = e.target.closest("[data-close]");
  if (closer) closeModal(closer.dataset.close);

  const adder = e.target.closest("[data-add]");
  if (adder) adder.dataset.add === "offre" ? openOffreForm() : openDomaineForm();
});

document.addEventListener("keydown", (e) => {
  if (e.key !== "Escape") return;
  ["confirm", "domaine", "offre"].forEach(closeModal);
});

async function reload() {
  try {
    const [domaines, offres] = await Promise.all([api(apiDomaine), api(apiOffre)]);
    state.domaines = domaines;
    state.offres = offres;
    render();
  } catch (err) {
    toast(err.message, "error");
  }
}

function render() {
  renderStats();
  renderDomaineOptions();
  renderOffres();
  renderDomaines();
  $("badgeOffres").textContent = state.offres.length;
  $("badgeDomaines").textContent = state.domaines.length;
}

function renderStats() {
  $("statOffres").textContent = state.offres.length;
  $("statDomaines").textContent = state.domaines.length;
}

function renderDomaineOptions() {
  const options = state.domaines
    .map((d) => `<option value="${d.id}">${esc(d.nom)}</option>`)
    .join("");

  $("filterDomaine").innerHTML = `<option value="">Tous les domaines</option>${options}`;
  $("filterDomaine").value = state.fDomaine;
  $("domaine_id").innerHTML =
    state.domaines.length > 0 ? options : `<option value="">— Aucun domaine —</option>`;
}

function renderOffres() {
  const q = state.qOffre.toLowerCase();
  const rows = state.offres
    .filter((o) => {
      const matchesSearch =
        !q ||
        o.titre.toLowerCase().includes(q) ||
        (o.description || "").toLowerCase().includes(q);
      const matchesDomaine = !state.fDomaine || String(o.domaine_id) === state.fDomaine;
      return matchesSearch && matchesDomaine;
    })
    .sort((a, b) => {
      switch (state.sort) {
        case "titre-asc":
          return a.titre.localeCompare(b.titre, "fr");
        case "salaire-desc":
          return b.salaire - a.salaire;
        case "salaire-asc":
          return a.salaire - b.salaire;
        default:
          return b.id - a.id;
      }
    });

  const empty = $("emptyOffres");
  empty.classList.toggle("hidden", rows.length > 0);
  empty.classList.toggle("flex", rows.length === 0);
  $("tbody").closest("div").classList.toggle("hidden", rows.length === 0);

  $("tbody").innerHTML = rows
    .map((o) => {
      const d = state.domaines.find((x) => x.id == o.domaine_id);
      return `
        <tr class="group transition hover:bg-slate-50/80">
          <td class="px-5 py-4 align-top">
            <p class="font-semibold text-slate-900">${esc(o.titre)}</p>
            <p class="mt-0.5 max-w-md truncate text-xs text-slate-500">${esc(o.description || "Sans description")}</p>
          </td>
          <td class="px-5 py-4 align-top">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
              <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>${esc(d?.nom || "Non classé")}
            </span>
          </td>
          <td class="px-5 py-4 align-top font-semibold tabular-nums text-slate-900">${money(o.salaire)} <span class="text-xs font-medium text-slate-400">DH</span></td>
          <td class="px-5 py-4 align-top">
            <div class="flex justify-end gap-1.5 transition sm:opacity-0 sm:group-hover:opacity-100">
              <button data-edit-offre="${o.id}" title="Modifier" class="grid h-9 w-9 place-items-center rounded-lg text-slate-500 transition hover:bg-indigo-50 hover:text-indigo-600">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
              </button>
              <button data-del-offre="${o.id}" title="Supprimer" class="grid h-9 w-9 place-items-center rounded-lg text-slate-500 transition hover:bg-rose-50 hover:text-rose-600">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14"/><path d="M10 11v5M14 11v5"/></svg>
              </button>
            </div>
          </td>
        </tr>`;
    })
    .join("");
}

function renderDomaines() {
  const q = state.qDomaine.toLowerCase();
  const rows = state.domaines.filter((d) => !q || d.nom.toLowerCase().includes(q));

  const empty = $("emptyDomaines");
  empty.classList.toggle("hidden", rows.length > 0);
  empty.classList.toggle("flex", rows.length === 0);

  $("domainesGrid").innerHTML = rows
    .map((d) => {
      const linked = state.offres.filter((o) => o.domaine_id == d.id);
      const avg = linked.length
        ? Math.round(linked.reduce((s, o) => s + (Number(o.salaire) || 0), 0) / linked.length)
        : 0;
      const initials = d.nom.slice(0, 2).toUpperCase();

      return `
        <div class="group rounded-2xl bg-white p-5 shadow-card transition hover:-translate-y-0.5 hover:shadow-soft">
          <div class="flex items-start justify-between">
            <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-violet-50 to-indigo-50 text-sm font-bold text-violet-700">${esc(initials)}</span>
            <div class="flex gap-1.5 transition sm:opacity-0 sm:group-hover:opacity-100">
              <button data-edit-domaine="${d.id}" title="Modifier" class="grid h-9 w-9 place-items-center rounded-lg text-slate-500 transition hover:bg-indigo-50 hover:text-indigo-600">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
              </button>
              <button data-del-domaine="${d.id}" title="Supprimer" class="grid h-9 w-9 place-items-center rounded-lg text-slate-500 transition hover:bg-rose-50 hover:text-rose-600">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14"/><path d="M10 11v5M14 11v5"/></svg>
              </button>
            </div>
          </div>
          <h3 class="mt-4 font-semibold text-slate-900">${esc(d.nom)}</h3>
          <p class="text-sm text-slate-500">${linked.length} offre${linked.length > 1 ? "s" : ""}</p>
          <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3 text-sm">
            <span class="text-slate-500">Salaire moyen</span>
            <span class="font-semibold tabular-nums text-slate-900">${money(avg)} DH</span>
          </div>
        </div>`;
    })
    .join("");
}

function openOffreForm(offre = null) {
  state.editOffre = offre;
  $("offreModalTitle").textContent = offre ? "Modifier l'offre" : "Nouvelle offre";
  $("offreSubmit").textContent = offre ? "Mettre à jour" : "Enregistrer";
  $("offreFormError").classList.add("hidden");

  const form = $("offreForm");
  form.reset();
  form.titre.value = offre?.titre ?? "";
  form.description.value = offre?.description ?? "";
  form.salaire.value = offre?.salaire ?? 0;
  if (offre) form.domaine_id.value = String(offre.domaine_id);

  openModal("offre");
  form.titre.focus();
}

function openDomaineForm(domaine = null) {
  state.editDomaine = domaine;
  $("domaineModalTitle").textContent = domaine ? "Modifier le domaine" : "Nouveau domaine";
  $("domaineFormError").classList.add("hidden");

  const form = $("domaineForm");
  form.reset();
  form.nom.value = domaine?.nom ?? "";

  openModal("domaine");
  form.nom.focus();
}

function askConfirm(title, text, onConfirm) {
  $("confirmTitle").textContent = title;
  $("confirmText").textContent = text;
  $("confirmYes").onclick = async () => {
    try {
      await onConfirm();
      closeModal("confirm");
    } catch (err) {
      toast(err.message, "error");
    }
  };
  openModal("confirm");
}

$("btnAddTop").onclick = () =>
  state.tab === "offres" ? openOffreForm() : openDomaineForm();

document.querySelectorAll(".tab").forEach((btn) => {
  btn.onclick = () => {
    state.tab = btn.dataset.tab;
    document.querySelectorAll(".tab").forEach((b) => b.classList.remove("tab-active"));
    btn.classList.add("tab-active");
    $("panel-offres").classList.toggle("hidden", state.tab !== "offres");
    $("panel-domaines").classList.toggle("hidden", state.tab !== "domaines");
  };
});

$("searchOffre").oninput = (e) => {
  state.qOffre = e.target.value.trim();
  renderOffres();
};

$("filterDomaine").onchange = (e) => {
  state.fDomaine = e.target.value;
  renderOffres();
};

$("sortBy").onchange = (e) => {
  state.sort = e.target.value;
  renderOffres();
};

$("searchDomaine").oninput = (e) => {
  state.qDomaine = e.target.value.trim();
  renderDomaines();
};

document.addEventListener("click", (e) => {
  const editOffre = e.target.closest("[data-edit-offre]");
  const delOffre = e.target.closest("[data-del-offre]");
  const editDomaine = e.target.closest("[data-edit-domaine]");
  const delDomaine = e.target.closest("[data-del-domaine]");

  if (editOffre) {
    const offre = state.offres.find((o) => o.id == editOffre.dataset.editOffre);
    if (offre) openOffreForm(offre);
    return;
  }

  if (delOffre) {
    const offre = state.offres.find((o) => o.id == delOffre.dataset.delOffre);
    if (!offre) return;
    askConfirm("Supprimer cette offre ?", `"${offre.titre}" sera définitivement supprimée.`, async () => {
      await api(`${apiOffre}?id=${offre.id}`, { method: "DELETE" });
      toast("Offre supprimée");
      await reload();
    });
    return;
  }

  if (editDomaine) {
    const domaine = state.domaines.find((d) => d.id == editDomaine.dataset.editDomaine);
    if (domaine) openDomaineForm(domaine);
    return;
  }

  if (delDomaine) {
    const domaine = state.domaines.find((d) => d.id == delDomaine.dataset.delDomaine);
    if (!domaine) return;
    askConfirm("Supprimer ce domaine ?", `"${domaine.nom}" sera définitivement supprimé.`, async () => {
      await api(`${apiDomaine}?id=${domaine.id}`, { method: "DELETE" });
      toast("Domaine supprimé");
      await reload();
    });
  }
});

$("offreForm").onsubmit = async (e) => {
  e.preventDefault();

  const form = e.target;
  const payload = {
    titre: form.titre.value.trim(),
    description: form.description.value.trim(),
    salaire: Number(form.salaire.value) || 0,
    domaine_id: Number(form.domaine_id.value),
  };

  const errorBox = $("offreFormError");
  const submit = $("offreSubmit");
  errorBox.classList.add("hidden");
  submit.disabled = true;
  submit.textContent = "Envoi...";

  try {
    if (state.editOffre) {
      await api(`${apiOffre}?id=${state.editOffre.id}`, { method: "PUT", body: payload });
      toast("Offre mise à jour");
    } else {
      await api(apiOffre, { method: "POST", body: payload });
      toast("Offre créée");
    }
    closeModal("offre");
    await reload();
  } catch (err) {
    errorBox.textContent = err.message;
    errorBox.classList.remove("hidden");
  } finally {
    submit.disabled = false;
    submit.textContent = state.editOffre ? "Mettre à jour" : "Enregistrer";
  }
};

$("domaineForm").onsubmit = async (e) => {
  e.preventDefault();

  const payload = { nom: e.target.nom.value.trim() };
  const errorBox = $("domaineFormError");
  errorBox.classList.add("hidden");

  try {
    if (state.editDomaine) {
      await api(`${apiDomaine}?id=${state.editDomaine.id}`, { method: "PUT", body: payload });
      toast("Domaine mis à jour");
    } else {
      await api(apiDomaine, { method: "POST", body: payload });
      toast("Domaine créé");
    }
    closeModal("domaine");
    await reload();
  } catch (err) {
    errorBox.textContent = err.message;
    errorBox.classList.remove("hidden");
  }
};

reload();
