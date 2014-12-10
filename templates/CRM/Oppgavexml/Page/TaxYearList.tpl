<div class="crm-content-block crm-block">
  <div id="help">
    The existing Tax Declaration Years are listed below. You can manage, load, export, reload or delete them from this screen 
    (depending on the status of the year selected). 
  </div>
  <div class="action-link">
    <a class="button new-option" href="{$add_url}">
      <span><div class="icon add-icon"></div>Ny Skatteinnberetninger</span>
    </a>
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
  <div class="action-link">
    <a class="button new-option" href="{$add_url}">
      <span><div class="icon add-icon"></div>Ny Skatteinnberetninger</span>
    </a>
  </div>
</div>
