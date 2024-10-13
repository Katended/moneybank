// Function to handle checkbox click events
function handleCheckboxClick(event) {
  const checkbox = event.target;

  // Check if the clicked element is a checkbox with the class 'row-checkbox'
  if (checkbox.matches('input[type="checkbox"].row-checkbox')) {
    // destroyAllDataTables();
    var formId = getCurrentFormId();

    if (checkbox.checked) {


      var ctype = getCategory(checkbox.value);

      var tid = checkbox.value;

      const params = {
        formId: formId,
        elementId: "div_name",
        action: "add",
        pageparams: ctype,
        urlpage: "",
        keyparam: tid,
        searchterm: "",
      };

      const isChecked = ["I", "G", "B"].some((letter) =>
        checkbox.value.includes(letter)
      );

      // Check if the checkbox value contains 'I' or 'S'
      if (isChecked) {
        loadClients(params).done(tid, () => {
          loadSavingAccounts(tid);
        });
      } else {
        loadAccount(tid);
      }
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
function loadSavingAccounts(sid) {
  var tags = document.getElementsByName("radiosclient");
  var TXTPAGE = "";

  for (var i = 0; i < tags.length; ++i) {
    if (tags[i].checked) {
      TXTPAGE = tags[i].value + "SAVACC";
    }
  }
  var formId = getCurrentFormId();

  return showValues(formId, "accounts", "search", TXTPAGE, "load.php", sid);
}

function loadAccount(accountId) {
  var formId = getCurrentFormId();

  return showValues(formId, "", "add", accountId, "load.php", accountId).done(
    accountId,
    () => {
      loadTransactions(accountId);
    }
  );
}

function loadTransactions(accountId) {
  // Get the current form ID
  var formId = getCurrentFormId();

  // Construct the URL for loading transactions
  var url =
    "load.php?act=edit&acc=" +
    $("#txtsavaccount").val() +
    "&product_prodid=" +
    $("#product_prodid").val();

  // Call the showValues function with the constructed parameters
  return showValues(formId, "savdata", "search", "SAVTRAN", url, accountId);
}
