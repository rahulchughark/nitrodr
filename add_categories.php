<?php

include('includes/header.php');
include_once 'helpers/DataController.php';
admin_protect();
$dataObj = new DataController();

$logs   = $dataObj->getLearningZoneLogsByUser();


// echo "<br><br><br><br><br><br>";
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

?>
<style>
   .category-management {
   height: calc(100vh - 210px);
   padding-top: 1rem;
   }
   .category-management .tree .category-list {
        max-height: calc(100vh - 306px);
        overflow: auto;
    }
   .category-management .tree ul {
   list-style: none;
   padding-left: 20px;
   }
   .category-management .tree li {
   margin: 5px 0;
   font-size: 16px;
   }
   .category-management .actions {
   margin-left: 10px;
   font-size: 0.9em;
   }
   .category-management .actions a {
   margin-right: 5px;
   cursor: pointer;
   }
   .category-management .hidden {
   display: none;
   }
   .category-management .caret {
   cursor: pointer;
   user-select: none;
   }
   .category-management .caret::before {
   content: "▶";
   display: inline-block;
   margin-right: 6px;
   }
   .category-management .caret-down::before {
   content: "▼";
   }
   .mdi-plus::before {
   font-weight: 800;
   }
   .modal {
   background: rgba(0, 0, 0, .32);
   backdrop-filter: blur(5px);
   }

   .tooltip-disabled {
    position: relative;
}

.disabled-class {
    opacity: 0.5;
    cursor: not-allowed !important;
    pointer-events: none !important;
}

.tooltip-disabled::after {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    margin-bottom: 8px;

    background: #333;
    color: #fff;
    padding: 6px 10px;
    font-size: 12px;
    white-space: nowrap;
    border-radius: 4px;

    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
    z-index: 9999;
}

.tooltip-disabled:hover::after {
    opacity: 1;
}
   
.disabled-tooltip {
    position: absolute;
    top: -36px;
    left: 50%;
    transform: translateX(-50%);
    background: #333;
    color: #fff;
    padding: 6px 10px;
    font-size: 12px;
    border-radius: 4px;
    white-space: nowrap;
    z-index: 99999;
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
}

.container-disabled {
    position: relative;
}

.container-disabled:hover .disabled-tooltip {
    opacity: 1;
}

@keyframes greenBlink {
    0% {
        background-color: #e9f7f8;
        box-shadow: 0 0 0 rgba(0, 188, 212, 0);
    }
    50% {
        background-color: #c8eef1;
        box-shadow: 0 0 12px rgba(0, 188, 212, 0.6);
    }
    100% {
        background-color: #e9f7f8;
        box-shadow: 0 0 0 rgba(0, 188, 212, 0);
    }
}

.blink-highlight {
    animation: greenBlink 1.2s ease-in-out infinite;
    border-radius: 6px;
}

#makeMasterBtn:disabled {
    cursor: not-allowed;
    opacity: 0.6;
}

/* 🟢 Custom Tooltip CSS */
.custom-tooltip {
    position: relative;
}


.custom-tooltip::after {
    content: attr(data-tooltip);
    position: absolute;
    top: 110%;
    left: 50%;
    transform: translateX(-50%);
    background: #333;
    color: #fff;
    padding: 6px 10px;
    font-size: 12px;
    white-space: nowrap;
    border-radius: 4px;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.2s ease-in-out;
    z-index: 1000;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.custom-tooltip::before {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    border-width: 5px;
    border-style: solid;
    border-color: transparent transparent #333 transparent;
    opacity: 0;
    transition: opacity 0.2s ease-in-out;
    z-index: 1000;
}

.custom-tooltip:hover::after,
.custom-tooltip:hover::before {
    opacity: 1;
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
               <div class="media bredcrum-title">
                  <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                  <div class="media-body">
                     <small class="text-muted">Home > Add Category</small>
                     <h4 class="font-size-14 m-0 mt-1">Create Categories</h4>
                  </div>
               </div>
               <div class="category-management" style="overflow: auto">
                  <h5>Category Management</h5>
                  <div class="tree">
                     <ul class="category-list" id="category-list"></ul>
                     <p>
                        <a  id="add-main-cat" class="btn btn-primary">
                        <span class="mdi mdi-plus"></span> Add New Main Category
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
                                 <button type="button" class="btn btn-primary px-4 submitBtnCls" id="submitBtn">Add</button>
                                 <button type="button" class="btn btn-danger" onclick="closeModal()">Cancel</button>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>

                  
<div class="table-responsive">
    <table id="example23"
           class="table display nowrap table-striped"
           data-height="wfheight"
           data-mobile-responsive="true"
           cellspacing="0"
           width="100%">

        <thead>
            <tr>
                <th data-sortable="true">S.No.</th>
                <th data-sortable="true">Type</th>
                <th data-sortable="true">Log</th>
                <th data-sortable="true">Timestamp</th>                
            </tr>
        </thead>

        <tbody>
            <!-- Row 1 -->
            <?php if (!empty($logs)) { 
            $i = 1;
            foreach ($logs as $log) { ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= htmlspecialchars($log['action_type']); ?></td>
                    <td><?= htmlspecialchars($log['description']); ?></td>
                    <td><?= date('d M Y, h:i A', strtotime($log['created_at'])); ?></td>
                </tr>
        <?php } 
        } else { ?>
                <tr>
                    <td colspan="4" class="text-center">No activity logs found</td>
                </tr>
        <?php } ?>

        </tbody>
    </table>
</div>


               </div>               
            </div>
         </div>
      </div>
   </div>
</div>

<!-- Modal -->
<div class="modal fade" id="moveFolderModal" tabindex="-1" aria-labelledby="moveFolderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form id="uploadForm" enctype="">
                
                <div class="modal-header">
                    <h5 class="modal-title align-self-center mt-0" id="modal-header-title">Move Category</h5>
                    <button type="button" class="close" aria-label="Close" onclick="$(this).closest('.modal').modal('hide')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body py-4">

                <?= $dataObj->renderMoveCategoryTree(); ?>

                <div class="text-center mt-3">
                    <button type="button" class="btn btn-primary mx-1" onclick="moveFilesFunction()">Move Files</button>
                    <button type="button" class="btn btn-primary mx-1" onclick="moveFolders()">Move Folders</button>
                    <!-- <button type="button" id="makeMasterBtn" class="btn btn-primary mx-1" onclick="moveFolders(true)">
                        Make Master</button> -->
                </div>

            </div>
                
            </form>
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
       let editTarget = null;
       let isEditMode = false;
       let parentCategory = null;
       let modalParentElPublic = null
   
       addMainBtn.onclick = () => openModal('main');
       submitBtn.onclick = () => submitCategory();
       
   
       function openModal(type, el = null, edit = false, oldName = "", parentId = 0) {
       
       
   
            let li = null;
   
             
           if (!parentId && el & type != 'sub') {
               const li = el.closest("li");
               if (li && li.dataset.id) {
                   parentId = li.dataset.id;
               }
           }else if(type == 'sub' && el){
               li = el.closest("li");
               parentId = li.dataset.id;
               console.log("li.dataset.id; chit",li.dataset.id);
           }
   
           // alert(parentId)
   
           parentCategory = parentId;  // ✅ store parent ID correctly
           modalType = type;
           modalParentEl = el;
           isEditMode = edit;
           editTarget = el;
           modalParentElPublic = modalParentEl;
           
   
           document.getElementById("category-name").value = oldName || "";
           document.getElementById("modal-title").innerText = edit
               ? "Edit Category"
               : (type === "main" ? "Add Main Category" : "Add Subcategory");
            
           document.getElementById("submitBtn").innerText = edit ? "Update" : "Add";
            
            
   
           document.getElementById("categoryModal").style.display = "block";
       }
   
   
   
       window.openModal = openModal;
   
       window.closeModal = function () {
           document.getElementById("categoryModal").style.display = "none";
           modalParentEl = null;
           editTarget = null;
           isEditMode = false;
       }
   
       window.submitCategory = function () {
           
          
           const name = document.getElementById("category-name").value.trim();
           if (!name) return;
           
   
           if (isEditMode && editTarget) {
           
               const li = editTarget.closest("li");
               const caret = li.querySelector(".caret");
               const target = caret || li.childNodes[0];
               if (caret) caret.textContent = name;
               else target.textContent = name;
               updateCategory(name, editTarget);


           } else {
            
               if (modalType === 'main') {
                  
                   addMainCategory(name);                           
                   submitFormCategory(name,'add',0);
               } else if (modalType === 'sub' && modalParentEl) {
                   
                   addSubcategory(modalParentEl, name);
                   submitFormCategory(name,'add',parentCategory);                         
               }
           }
   
           closeModal();
           setTimeout(() => location.reload(), 2000);
       }



       window.updateCategory = function (name, editTarget) {
                    if (!name) {
                        toastr.error("Category name is required");
                        return;
                    }

                    if (!editTarget) {
                        toastr.error("No category selected for update");
                        return;
                    }

                    const li = editTarget.closest("li");
                    const categoryId = li.dataset.id;

                    $.ajax({
                        url: "categories_query.php",
                        type: "POST",
                        data: { id: categoryId, category_name: name, is_update: true },
                        dataType: "json",
                        success: function (response) {
                            if (response.status === "success") {
                                // ✅ Update UI text
                                const caret = li.querySelector(".caret");
                                if (caret) {
                                    caret.textContent = name;
                                } else {
                                    li.querySelector(".fi-row").childNodes[0].textContent = name;
                                }

                                toastr.success("Category Updated Successfully");
                            } else {
                                toastr.error(response.message || "Failed to update");
                            }
                        },
                        error: function () {
                            toastr.error("Error: Something went wrong");
                        }
                    });
                };
   
       
   
    function addMainCategory(name, id = null) {
           const li = document.createElement("li");
           if (id) li.dataset.id = id; // store category ID
   
           li.innerHTML = `
               <div class="fi-row">
                   <span class="caret" onclick="toggleCaret(this)">${name}</span>
                   <span class="actions">
                       <a class="btn btn-primary2 btn-xs px-2" 
                       onclick="openModal('sub', this, false, '', ${id ? id : 0})">
                       <span class="mdi mdi-plus"></span></a>
                       <a onclick="openModal('main', this, true, '${name}', ${id || 0})" 
                       class="btn btn-primary btn-xs px-2"><i class="mdi mdi-pencil"></i></a>
                       <a  onclick="removeCategory(this, ${id || 0})" 
                       class="btn btn-danger btn-xs px-2 rahul65"><i class="mdi mdi-trash-can-outline"></i></a>
                       <button type="button" onclick="moveFolderManage(${id})" data-toggle="modal" data-target="#moveFolderModal" class="btn gggg btn-primary btn-xs px-2"><i class="mdi mdi-arrow-all"></i></button>
                   </span>                    
               </div>
               <ul class="nested hidden"></ul>
           `;
   
           // document.getElementById("category-list").appendChild(li);
           return li;
       }
   
   
       // Add subcategory
       window.addSubcategory = function (el, name, id = null) {
               const li = document.createElement("li");
               if (id) li.dataset.id = id;
   
               // ✅ Get parent ID from closest LI
               let parentId = el.closest("li").dataset.id || 0;
   
               li.innerHTML = `
                   <div class="fi-row">
                       <span class="caret" onclick="toggleCaret(this)">${name}</span>
                       <span class="actions">
                           <a class="btn btn-primary2 btn-xs px-2" 
                           onclick="openModal('sub', this, false, '', ${id ? id : parentId})">
                           <span class="mdi mdi-plus"></span></a>
                           <a onclick="openModal('sub', this, true, '${name}', ${id || parentId})" 
                           class="btn btn-primary btn-xs px-2"><i class="mdi mdi-pencil"></i></a>
                           <a  onclick="removeCategory(this, ${id || 0})" 
                           class="btn btn-danger btn-xs px-2 rahul87"><i class="mdi mdi-trash-can-outline"></i></a>
                           <button type="button" onclick="moveFolderManage(${id})" data-toggle="modal" data-target="#moveFolderModal"
                                            class="btn btn-primary btn-xs px-2"><i class="mdi mdi-arrow-all"></i></button>
                       </span>
                   </div>
                   <ul class="nested hidden"></ul>`;
   
               let parentUL = el.closest("li").querySelector("ul.nested");
               if (!parentUL) {
                   parentUL = document.createElement("ul");
                   parentUL.classList.add("nested");
                   el.closest("li").appendChild(parentUL);
               }
   
               parentUL.classList.remove("hidden");
               // parentUL.appendChild(li);
               return li;
           }
   
       window.toggleCaret = function (el) {
           el.classList.toggle("caret-down");
           const ul = el.closest("li").querySelector("ul.nested");
           if (ul) ul.classList.toggle("hidden");
       }
   
       window.removeCategory = function (el, id) {
        
                swal({
                    title: "Are you sure?",                    
                    text: "Do you want to delete this category?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                },function() {                 
                        
                        // 🔹 Remove from UI
                        el.closest("li").remove();

                        // 🔹 AJAX call to delete from DB
                        $.ajax({
                            url: "categories_query.php",
                            type: "POST",
                            data: { is_deleted : true, id: id },
                            success: function (response) {
                                // console.log("Category deleted:", response);
                              toastr.success('Category Removed Successfully');
                              setTimeout(() => location.reload(), 2000);
                            },
                            error: function () {
                              toastr.error('Error: Something went wrong');
                                // console.error("Error deleting category");
                            }
                        });
                    
                });
            };
   
       // Close modal when clicking outside
       window.onclick = function (e) {
           const modal = document.getElementById("categoryModal");
           if (e.target === modal) {
               closeModal();
           }
       };
   
       function submitFormCategory(name, type = "add", parentCategory = 0) {
                              
                   $.ajax({
                       url: "ajax_categories_submit.php",
                       type: "POST",
                       data: { category_name: name, type: type, parent_id: parentCategory },
                       dataType: "json",
                       success: function (response) {
                           if (response.status === "success") {
                              toastr.success('Category Created Successfully');
                              let newId = response.id;
   
                                let newLi = document.createElement("li");
                                   newLi.dataset.id = newId;
                                   newLi.innerHTML = `
                                       <div class="fi-row">
                                           <span class="caret" onclick="toggleCaret(this)">${name}</span>
                                           <span class="actions">
                                               <a class="btn btn-primary2 btn-xs px-2" onclick="openModal('sub', this, false, '', ${newId})">
                                                   <span class="mdi mdi-plus"></span>
                                               </a>
                                               <a onclick="openModal('${modalType}', this, true, '${name}', ${newId})" 
                                               class="btn btn-primary btn-xs px-2">
                                               <i class="mdi mdi-pencil"></i>
                                               </a>
                                               <a  onclick="removeCategory(this, ${newId})" class="btn btn-danger btn-xs px-2 rahul54">
                                               <i class="mdi mdi-trash-can-outline"></i>
                                               </a>
                                               <button type="button" onclick="moveFolderManage(${newId})" data-toggle="modal" data-target="#moveFolderModal"
                                            class="btn btn-primary btn-xs px-2"><i class="mdi mdi-arrow-all"></i></button>
                                           </span>
                                       </div>
                                       <ul class="nested hidden"></ul>
                                   `;
   
                                   if (modalType === 'main') {                                     
                                       document.querySelector("#category-list").appendChild(newLi);
   
                                   } else if (modalType === 'sub') {
                                       // Append inside the clicked parent li’s nested <ul>
                                       let parentLi = modalParentElPublic.closest("li");
                                       let nestedUl = parentLi.querySelector("ul.nested");
   
                                       if (!nestedUl) {
                                           // If no nested ul exists, create one
                                           nestedUl = document.createElement("ul");
                                           nestedUl.classList.add("nested");
                                           parentLi.appendChild(nestedUl);
                                       }
   
                                       nestedUl.appendChild(newLi);
                                   }                                                              
                           
                           }else{
                              toastr.error('Error: Something went wrong');
   
                           }
                       }
                   });
               }
   loadCategories();
   
   function loadCategories() {
   $.ajax({
   url: "categories_query.php",
   type: "GET",
   dataType: "json",
   success: function (response) {
       if (response.status === "success") {
           let categories = response.data;
   
           // convert into tree (group by parent_id)
           let tree = {};
           categories.forEach(cat => {
               if (!tree[cat.parent_id]) tree[cat.parent_id] = [];
               tree[cat.parent_id].push(cat);
           });
   
        function renderTree(parentId = 0) {
    if (!tree[parentId]) return "";

    const style = parentId === 0 ? "style='margin-bottom: 27px;'" : "";
    let html = `<ul ${style}>`;

                    tree[parentId].forEach(cat => {
                        const hasChildren = tree[cat.id] && tree[cat.id].length > 0;
                        const disableDelete = cat.isData === true;

                        html += `
                            <li data-id="${cat.id}">
                                <div class="fi-row">
                                    <span class="${hasChildren ? 'caret' : ''}"
                                        ${hasChildren ? 'onclick="toggleCaret(this)"' : ''}>
                                        ${cat.name}
                                    </span>

                                    <span class="actions">
                                        <a class="btn btn-primary2 btn-xs px-2 custom-tooltip"
                                           data-tooltip="Add Sub-Category"
                                           onclick="openModal('sub', this, false, '', ${cat.id})">
                                            <span class="mdi mdi-plus"></span>
                                        </a>

                                        <a onclick="openModal('sub', this, true, '${cat.name}', ${cat.id})"
                                           class="btn btn-primary btn-xs px-2 custom-tooltip"
                                           data-tooltip="Edit Category">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>

                                        ${
                                            disableDelete
                                            ? `
                                                <span class="custom-tooltip" data-tooltip="This category contains data">
                                                    <a class="btn btn-danger btn-xs px-2 disabled-class">
                                                        <i class="mdi mdi-trash-can-outline"></i>
                                                    </a>
                                                </span>
                                            `
                                            : `
                                                <a onclick="removeCategory(this, ${cat.id})"
                                                   class="btn btn-danger btn-xs px-2 custom-tooltip"
                                                   data-tooltip="Delete Category">
                                                    <i class="mdi mdi-trash-can-outline"></i>
                                                </a>
                                            `
                                        }

                                        <button type="button"
                                                onclick="moveFolderManage(${cat.id})"
                                                data-toggle="modal"
                                                data-target="#moveFolderModal"
                                                class="btn gggg btn-primary btn-xs px-2 custom-tooltip"
                                                data-tooltip="Move Folder">
                                            <i class="mdi mdi-arrow-all"></i>
                                        </button>

                                        ${
                                            parentId !== 0 
                                            ? `
                                                <button type="button"
                                                        onclick="makeMaster(${cat.id})"
                                                        class="btn btn-primary2 btn-xs px-2 custom-tooltip"
                                                        data-tooltip="Make this folder a Master Category">
                                                    <i class="mdi mdi-arrow-up-bold"></i>
                                                </button>
                                            `
                                            : ''
                                        }

                                    </span>
                                </div>

                                ${hasChildren ? `<ul class="nested hidden">${renderTree(cat.id)}</ul>` : ""}
                            </li>
                        `;
                    });

                    html += "</ul>";
                    return html;
}

document.querySelector("#category-list").innerHTML = renderTree(0);


        // function renderTree(parentId = 0) {
        //                 if (!tree[parentId]) return "";
 
        //                 let html = "<ul>";
        //                 tree[parentId].forEach(cat => {
        //                     const hasChildren = tree[cat.id] && tree[cat.id].length > 0;
 
        //                     html += `
        //                         <li data-id="${cat.id}">
        //                             <div class="fi-row">
        //                                 <span class="${hasChildren ? 'caret' : ''}"
        //                                     ${hasChildren ? 'onclick="toggleCaret(this)"' : ''}>
        //                                     ${cat.name}
        //                                 </span>
        //                                 <span class="actions">
        //                                     <a class="btn btn-primary2 btn-xs px-2"
        //                                     onclick="openModal('sub', this, false, '', ${cat.id})">
        //                                     <span class="mdi mdi-plus"></span>
        //                                     </a>
        //                                     <a onclick="openModal('sub', this, true, '${cat.name}', ${cat.id})"
        //                                     class="btn btn-primary btn-xs px-2"><i class="mdi mdi-pencil"></i></a>
        //                                     if(cat?.isData)
        //                                     <a  onclick="removeCategory(this, ${cat.id})"
        //                                     class="btn btn-danger btn-xs px-2 "><i class="mdi mdi-trash-can-outline"></i></a>
        //                                     else
        //                                     <a disabled
        //                                     class="btn btn-danger btn-xs px-2 "><i class="mdi mdi-trash-can-outline"></i></a>
                                            

        //                                     <button type="button" onclick="moveFolderManage(${cat.id})" data-toggle="modal" data-target="#moveFolderModal"
        //                                     class="btn btn-primary btn-xs px-2"><i class="mdi mdi-arrow-all"></i></button>
        //                                 </span>
        //                             </div>
        //                             ${hasChildren ? `<ul class="nested hidden">${renderTree(cat.id)}</ul>` : ""}
        //                         </li>
        //                     `;
        //                 });
        //                 html += "</ul>";
        //                 return html;
        //             }
 
   
        //    document.querySelector("#category-list").innerHTML = renderTree(0);
       }
   }
   });
   }
   
   
   
   });
   
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // ✅ STOP checkbox + label bubbling
    document.querySelectorAll('.custom-checkbox input, .custom-checkbox label')
        .forEach(el => {
            el.addEventListener('click', e => e.stopPropagation());
        });

    // ✅ ADD end-folder class (leaf nodes)
    document.querySelectorAll('.file-tree .folder').forEach(folder => {
        if (!folder.querySelector(':scope > ul')) {
            folder.classList.add('end-folder');
        }
    });

  
    document.querySelectorAll('.file-tree .folder')
        .forEach(folder => folder.classList.add('active'));

  
    document.addEventListener("click", function (e) {
        const folder = e.target.closest(".file-tree .folder");
        if (!folder) return;

      
        if (folder.classList.contains('end-folder')) return;

        
        folder.classList.toggle("active");
    });

    // ✅ Prevent selecting disabled category
    $(document).on('change', 'input[name="change_cat"]', function() {
        const container = $(this).closest('.fi');
        if (container.hasClass('tab-active')) {
            $(this).prop('checked', false);
            // toastr.error('You cannot select this category as parent');
        }
    });

});
</script>

<script>

moveFolderID = 0;

// function moveFolderManage(categoryId) {
//     moveFolderID = categoryId;
//     disableContainer(categoryId);
// }
function moveFolderManage(categoryId) {
    moveFolderID = categoryId;
    disableContainer(categoryId);

    // checkIfParentCategory(categoryId);
}


// function checkIfParentCategory(categoryId) {
//     $.ajax({
//         url: 'ajax_check_category_parent.php',
//         type: 'POST',
//         dataType: 'json',
//         data: { category_id: categoryId },
//         success: function (res) {
//             if (res.is_parent) {
//                 $('#makeMasterBtn').prop('disabled', true);
//             } else {
//                 $('#makeMasterBtn').prop('disabled', false);
//             }
//         },
//         error: function () {
//             // Fail safe: disable button
//             $('#makeMasterBtn').prop('disabled', true);
//         }
//     });
// }



function moveFolders(isMaster = false) {

    const selected     = document.querySelector('input[name="change_cat"]:checked');
    const moveFolderID = window.moveFolderID || null;

    // 🔹 Validation
    if (!moveFolderID) {
        toastr.error("Unable to find master folder");
        return;
    }

    if (!isMaster && !selected) {
        toastr.error("Please select a category");
        return;
    }

    // 🔹 UI text based on mode
    const textToastr = isMaster
        ? "You want to make this folder as Parent?"
        : "You want to move folders to the selected category?";

    const buttonText = isMaster
        ? "Yes, Make Parent"
        : "Yes, Move";

    // 🔹 Parent category logic
    const targetCategoryId = isMaster ? 0 : selected.value;

    // 🔹 Confirmation box
    swal({
        title: "Are you sure?",
        text: textToastr,
        type: "warning",
        showCancelButton: true,
        confirmButtonText: buttonText,
        confirmButtonColor: "#28a745",
        closeOnConfirm: false
    }, function () {

        $.ajax({
            url: "categories_update.php",
            type: "POST",
            dataType: "json",
            data: {
                parentFolder: targetCategoryId,
                childFolder: moveFolderID,
                isMaster: isMaster ? 1 : 0
            },
            success(response) {

                if (response.status === "success") {
                    swal("Success!", "Folders moved successfully.", "success");

                    setTimeout(() => location.reload(), 1500);
                } else {
                    swal("Error!", response.message || "Failed to move folders.", "error");
                }
            },
            error() {
                swal("Error!", "Server error occurred.", "error");
            }
        });

    });
}

function makeMaster(id) {
    swal({
        title: "Are you sure?",
        text: "Do you want to make this folder a Master Category (Root Level)?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, Make Master",
        confirmButtonColor: "#17a2b8",
        closeOnConfirm: false
    }, function () {

        $.ajax({
            url: "categories_update.php",
            type: "POST",
            dataType: "json",
            data: {
                parentFolder: 0,
                childFolder: id,
                isMaster: 1
            },
            success(response) {
                if (response.status === "success") {
                    swal("Success!", "Folder promoted to Master Category.", "success");
                    setTimeout(() => location.reload(), 2000);
                } else {
                    swal("Error!", response.message || "Failed to update.", "error");
                }
            },
            error() {
                swal("Error!", "Server error occurred.", "error");
            }
        });

    });
}


</script>

<script>
function moveFilesFunction() {
     const fromVar = window.moveFolderID || null;
     const selectedCheckbox  = document.querySelector('input[name="change_cat"]:checked');
     
    if (!fromVar || !selectedCheckbox) {
        toastr.error("Invalid category selection");
        return;
    }

    toVar = selectedCheckbox.value;
    labelName = document.getElementById('label-folder-name-' + toVar).getAttribute('data-label');


    // alert(fromVar);
    // alert(toVar);
    // alert(labelName);
    //  return ;

    swal({
        title: "Are you sure?",
        text: "You want to move files and folders to the selected folder?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, Move Files",
        confirmButtonColor: "#28a745",
        closeOnConfirm: false
    }, function () {

        $.ajax({
            url: "ajax_file_folder_change.php",
            type: "POST",
            dataType: "json",
            data: {
                fromVar: fromVar,
                toVar: toVar,
                name:labelName
            },
            success: function (response) {

                if (response.status === "success") {
                    swal("Success!", response.message, "success");

                    setTimeout(function () {
                        location.reload();
                    }, 1500);

                } else {
                    swal("Error!", response.message || "Operation failed", "error");
                }
            },
            error: function () {
                swal("Error!", "Server error occurred.", "error");
            }
        });

    });
}
</script>
<script>
// function disableContainer(containerId) {

//     // 🔄 Reset all containers starting with container_dv_
//     const allContainers = document.querySelectorAll('[id^="container_dv_"]');

//     allContainers.forEach(el => {
//         el.style.opacity = '1';
//         el.style.cursor = 'default';
//         el.style.pointerEvents = 'auto';
//     });

//     // Accept either number or full id
//     if (!isNaN(containerId)) {
//         containerId = 'container_dv_' + containerId;
//     }

//     const el = document.getElementById(containerId);

//     if (!el) {
//         console.warn('Container not found:', containerId);
//         return;
//     }

//     // 🔒 Disable selected container
//     el.style.opacity = '0.5';
//     el.style.cursor = 'not-allowed';
//     // el.style.pointerEvents = 'none'; // enable if you want full disable
// }

function disableContainer(containerId) {

    // 🔄 FULL reset all containers
    document.querySelectorAll('[id^="container_dv_"]').forEach(el => {
        console.log(el);
        el.style.opacity = '1';
        el.style.cursor = 'default';
        el.style.backgroundColor = ''; // ✅ IMPORTANT FIX
        el.classList.remove('blink-highlight', 'container-disabled', 'tab-active');

        const tooltip = el.querySelector('.disabled-tooltip');
        if (tooltip) tooltip.remove();
    });

    // Normalize ID
    if (!isNaN(containerId)) {
        containerId = 'container_dv_' + containerId;
    }

    const el = document.getElementById(containerId);
    if (!el) return;

    // 🔒 Disable visuals
    el.style.cursor = 'not-allowed';
    el.classList.add('blink-highlight', 'container-disabled', 'tab-active');

    // ✅ Tooltip
    const tooltip = document.createElement('span');
    tooltip.className = 'disabled-tooltip';
    tooltip.innerText = 'You cannot select this';
    el.appendChild(tooltip);

    // ⏱ Stop blinking after 5s → keep ONLY current one green
    setTimeout(() => {
        el.classList.remove('blink-highlight');
        el.style.backgroundColor = '#e9f7f8';
        el.style.opacity = '0.95';
    }, 5000);
}
</script>