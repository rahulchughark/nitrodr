<?php include('includes/header.php');
admin_protect();
?>

<style>

    .category-management {
        height: calc(100vh - 210px);
        padding-top: 1rem;
    }

    .tree ul {
      list-style: none;
      padding-left: 20px;
    }
    .tree li {
      margin: 5px 0;
    }
    .actions {
      margin-left: 10px;
      font-size: 0.9em;
    }
    .actions a {
      margin-right: 5px;
      cursor: pointer;
      color: purple;
    }
    .hidden {
      display: none;
    }
    .caret {
      cursor: pointer;
      user-select: none;
    }
    .caret::before {
      content: "▶";
      display: inline-block;
      margin-right: 6px;
    }
    .caret-down::before {
      content: "▼";
    }

    .modal {
        background: rgba(0, 0, 0, .32);
        backdrop-filter: blur(5px);
    }

  

    

</style>

<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="main-content">
    <div class="page-content learning-centre-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body admin_kra1">
                            <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">
                                    <small class="text-muted">Home > Add Category</small>
                                    <h4 class="font-size-14 m-0 mt-1">Create Categories</h4>
                                </div>
                            </div>
                            <div class="category-management">
                                <h5>Category Management</h5>
                                <div class="tree">
                                    <ul id="category-list"></ul>
                                    <p>
                                        <a id="add-main-cat" class="btn btn-primary">
                                        <span class="mdi mdi-plus"></span> Add <span style='color: magenta;'>New</span> Main Category
                                        </a>
                                    </p>
                                    </div>

                                <!-- Modal -->
                                <div id="categoryModal" class="modal" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label id="modal-title">Add Category</label>
                                                    <input type="text" id="category-name" class="form-control" placeholder="Enter name" />
                                                </div>
                                                <div class="modal-buttons">
                                                    <button type="button" class="btn btn-primary px-4" id="submitBtn">Add</button>
                                                    <button type="button" class="btn btn-danger" onclick="closeModal()">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

        <?php include('includes/footer.php') ?>

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const addMainBtn = document.getElementById("add-main-cat");
                const submitBtn = document.getElementById("submitBtn");

                let modalType = 'main';
                let modalParentEl = null;

                addMainBtn.onclick = () => openModal('main');
                submitBtn.onclick = () => submitCategory();

                function openModal(type, el = null) {
                modalType = type;
                modalParentEl = el;
                document.getElementById("category-name").value = "";
                document.getElementById("modal-title").innerText =
                    type === 'main' ? "Add Main Category" : "Add Subcategory";
                document.getElementById("categoryModal").style.display = "block";
                }

                window.openModal = openModal;

                window.closeModal = function () {
                document.getElementById("categoryModal").style.display = "none";
                modalParentEl = null;
                }

                window.submitCategory = function () {
                const name = document.getElementById("category-name").value.trim();
                if (!name) return;

                if (modalType === 'main') {
                    addMainCategory(name);
                } else if (modalType === 'sub' && modalParentEl) {
                    addSubcategory(modalParentEl, name);
                }

                closeModal();
                }

                function addMainCategory(name) {
                const li = document.createElement("li");
                li.innerHTML = `
                    <div class="fi-row">
                    <span class="caret" onclick="toggleCaret(this)">${name}</span>
                    <span class="actions">
                    [<a onclick="openModal('sub', this)">Add Subcategory</a>]
                    <a onclick="editCategory(this)" class="btn btn-primary btn-xs px-2"><i class="mdi mdi-pencil"></i></a>
                    <a onclick="removeCategory(this)" class="btn btn-danger btn-xs px-2"><i class="mdi mdi-trash-can-outline"></i></a>
                    </span>                    
                    </div>
                    <ul class="nested hidden"></ul>
                `;
                document.getElementById("category-list").appendChild(li);
                }

                window.addSubcategory = function (el, name) {
                const li = document.createElement("li");
                li.innerHTML = `
                    ${name}
                    <span class="actions">
                    [<a onclick="openModal('sub', this)">Add</a>]
                    <a onclick="editCategory(this)"class="btn btn-primary btn-xs px-2"><i class="mdi mdi-pencil"></i></a>
                    <a onclick="removeCategory(this)" class="btn btn-danger btn-xs px-2"><i class="mdi mdi-trash-can-outline"></i></a>
                    </span>
                `;

                let parentUL = el.closest("li").querySelector("ul.nested");
                if (!parentUL) {
                    parentUL = document.createElement("ul");
                    parentUL.classList.add("nested");
                    el.closest("li").appendChild(parentUL);
                }

                parentUL.classList.remove("hidden");
                parentUL.appendChild(li);
                }

                window.toggleCaret = function (el) {
                el.classList.toggle("caret-down");
                const ul = el.closest("li").querySelector("ul.nested");
                if (ul) ul.classList.toggle("hidden");
                }

                window.editCategory = function (el) {
                const li = el.closest("li");
                const caret = li.querySelector(".caret");
                const target = caret || li.childNodes[0];
                const oldName = target.textContent.trim();
                const newName = prompt("Edit name:", oldName);
                if (newName) {
                    if (caret) caret.textContent = newName;
                    else target.textContent = newName;
                }
                }

                window.removeCategory = function (el) {
                if (confirm("Delete this category?")) {
                    el.closest("li").remove();
                }
                }

                // Close modal when clicking outside
                window.onclick = function (e) {
                const modal = document.getElementById("categoryModal");
                if (e.target === modal) {
                    closeModal();
                }
                };
            });
            </script>