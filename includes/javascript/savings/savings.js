// Function to handle checkbox click events
function handleCheckboxClick(event) {
  const checkbox = event.target;

  // Check if the clicked element is a checkbox with the class 'row-checkbox'
  if (checkbox.matches('input[type="checkbox"].row-checkbox')) {
    var formId = getCurrentFormId();

    if (checkbox.checked) {
      const params = {
        formId: formId,
        elementId: "div_name",
        action: "add",
        pageparams: getCategory(checkbox.value),
        urlpage: "",
        keyparam: checkbox.value,
        searchterm: "",
      };

      loadClients(params).done((formId) => {
        loadSavingAccounts(formId);
      });
    }
  }
}

// Initialize event listeners
function initEventListeners() {
  document.addEventListener("click", handleCheckboxClick);
}

// Get savings accounts
// The page should have table called 'accounts' where the accounts will be loaded
// The page should have an input element with id client_idno used to store the clientId
function loadSavingAccounts(formId) {
  var tags = document.getElementsByName("radiosclient");
  var TXTPAGE = "";

  for (var i = 0; i < tags.length; ++i) {
    if (tags[i].checked) {
      TXTPAGE = tags[i].value + "SAVACC";
    }
  }

  return showValues(
    formId,
    "accounts",
    "search",
    TXTPAGE,
    "load.php",
    $("#client_idno").val()
  );
}
