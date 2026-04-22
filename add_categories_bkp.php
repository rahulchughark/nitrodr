<?php include('includes/header.php');
admin_protect();
?>

<style>

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

                            <form action="" class="mt-4">
                                <div class="category-management">
                                    <h3>Category Management</h3>
                                    <div class="tree">
                                        <ul id="category-list"></ul>
                                        <p>
                                            <a onclick="addMainCategory()" style="color: purple;">
                                            [+ Add <span style='color: magenta;'>New</span> Main Category]
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </form>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

        <?php include('includes/footer.php') ?>

        <script>
            function addMainCategory() {
                let name = prompt("Enter main category name:");
                if (!name) return;

                const li = document.createElement("li");

                li.innerHTML = `
                <span class="caret" onclick="toggleCaret(this)">${name}</span>
                <span class="actions">
                    [<a onclick="addSubcategory(this)">Add Subcategory</a>] 
                    <a onclick="editCategory(this)">✎</a> 
                    <a onclick="removeCategory(this)">🗑️</a>
                </span>
                <ul class="nested hidden"></ul>
                `;

                document.getElementById("category-list").appendChild(li);
            }

            function addSubcategory(el) {
                const name = prompt("Enter subcategory name:");
                if (!name) return;

                const li = document.createElement("li");
                li.innerHTML = `
                ${name}
                <span class="actions">
                    [<a onclick="addSubcategory(this)">Add</a>] 
                    <a onclick="editCategory(this)">✎</a> 
                    <a onclick="removeCategory(this)">🗑️</a>
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

            function toggleCaret(el) {
                el.classList.toggle("caret-down");
                const ul = el.closest("li").querySelector("ul.nested");
                if (ul) ul.classList.toggle("hidden");
            }

            function editCategory(el) {
                const li = el.closest("li");
                const caret = li.querySelector(".caret");
                const textNode = caret ? caret : li.firstChild;

                const oldName = textNode.textContent.trim();
                const newName = prompt("Edit name:", oldName);
                if (newName) {
                if (caret) {
                    caret.textContent = newName;
                } else {
                    li.firstChild.textContent = newName;
                }
                }
            }

            function removeCategory(el) {
                const li = el.closest("li");
                if (confirm("Are you sure you want to delete this category?")) {
                li.remove();
                }
            }
        </script>