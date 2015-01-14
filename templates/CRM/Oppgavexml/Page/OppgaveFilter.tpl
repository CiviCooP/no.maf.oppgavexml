<div class="crm-activity-selector-donor_type">
  <div class="crm-accordion-wrapper crm-search_filters-accordion">
    <div class="crm-accordion-header"> Filter by ContactID or Donor Type </div>
    <div class="crm-accordion-body">
      <div id="searchOptions" class="border form-layout-compressed">
        <div class="crm-contact-form-block-contact_id_filter crm-inline-edit-field">
          <label for="contact_id_filter">ContactID</label>
          <select id="contact_id_filter" class="form-select" name="contact_id_filter" onChange="filterContactId({$request_tax_year});">
            {if empty($request_contact_id)}
              <option selected value="All">All</option>
            {else}
              <option value="All">All</option>
            {/if}
            {foreach from=$oppgaves key=oppgave_id item=oppgave}
              {if $oppgave.contact_id eq $request_contact_id}
                <option selected value={$oppgave.contact_id}>
              {else}   
                <option value={$oppgave.contact_id}>
              {/if}
              {$oppgave.contact_id}</option>
            {/foreach}  
          </select>
        </div>
        <div class="crm-contact-form-block-donor_type_filter crm-inline-edit-field">
          <label for="donor_type_filter">Donor Type</label>
          <select id="donor_type_filter" class="big form-select" name="donor_type_filter" onChange="filterDonorType({$request_tax_year});">
            {foreach from=$donor_type_options item=donor_type_option} 
              {if $donor_type_option eq $request_donor_type}
                <option selected value={$donor_type_option}>
              {else}   
                <option value={$donor_type_option}>
              {/if}
              {$donor_type_option}</option>
            {/foreach}
          </select>
        </div>
      </div>
    </div>
  </div>
</div>
{literal}
  <script type="text/javascript">
    function filterDonorType(oppgave_year) {
      var donor_type = document.getElementById("donor_type_filter").value;
      if (donor_type === 'All') {
        var path = CRM.url('civicrm/oppgavelist', {year:oppgave_year});
        window.location.replace(path);
      } else {
        var path = CRM.url('civicrm/oppgavelist', {year:oppgave_year, cid:0, dt:donor_type});
        window.location.replace(path);
      }
      return true;
    }
  </script>
  <script type="text/javascript">
    function filterContactId(oppgave_year) {
      var contact_id = document.getElementById("contact_id_filter").value;
      if (contact_id === 'All') {
        var path = CRM.url('civicrm/oppgavelist', {year:oppgave_year});
        window.location.replace(path);
      } else {
        var path = CRM.url('civicrm/oppgavelist', {year:oppgave_year, dt:'', cid:contact_id});
        window.location.replace(path);
      }
      return true;
    }
  </script>
{/literal}
