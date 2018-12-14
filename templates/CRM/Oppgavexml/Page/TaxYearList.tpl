<div class="crm-content-block crm-block">
  <div id="help">
    The existing Tax Declaration Years are listed below. You can manage or delete them from this screen 
    (depending on the status of the year selected). 
  </div>
  <div class="crm-block crm-form-block">
    <form action="{crmURL p='civicrm/oppgave/load'}" method="GET">
      <div class="form-layout">
        <label for="year">{ts}Year{/ts}</label> <input type="text" name="year" size="4" value="{$current_year}" class="small crm-form-text" />
        <input type="submit" value="{ts}Load{/ts}" class="crm-form-submit default" />
      </div>
    </form>
  </div>
  <div id="skatteinnberetninger_wrapper" class="dataTables_wrapper">
    <table id="skatteinnberetninger-table" class="display">
      <thead>
        <tr>
          <th class="sorting-disabled" rowspan="1" colspan="1">Year</th>
          <th class="sorting-disabled" rowspan="1" colspan="1">Status</th>
          <th class="sorting_disabled" rowspan="1" colspan="1"></th>
        </tr>
      </thead>
      <tbody>
        {assign var="row_class" value="odd-row"}
        {foreach from=$skatteinnberetninger key=year item=year_data}
          <tr id="row1" class={$row_class}>
            <td>{$year}</td>
            <td>{$year_data.status}</td>
            <td>
              <span>
                {foreach from=$year_data.actions item=action_link}
                  {$action_link}
                {/foreach}

              </span>
            </td>
          </tr>
          {if $row_class eq "odd-row"}
            {assign var="row_class" value="even-row"}
          {else}
            {assign var="row_class" value="odd-row"}                        
          {/if}
        {/foreach}
      </tbody>
    </table>    
  </div>
  </div>
</div>
