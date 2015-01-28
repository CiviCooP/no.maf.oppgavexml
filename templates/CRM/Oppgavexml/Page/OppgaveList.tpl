<div class="crm-content-block crm-block">
  {if $display_type eq 'year'}
    <div id="help">
      The existing donor oppgave are listed below. You can edit, delete or add a new one from this screen. 
    </div>
    <div class="action-link">
      <a class="button new-option" href="{$add_url}">
        <span><div class="icon add-icon"></div>Ny Donoroppgave</span>
      </a>
    </div>
  {/if}
  {include file='CRM/Oppgavexml/Page/OppgaveFilter.tpl'}
  {include file='CRM/common/jsortable.tpl'}
  <div id="oppgave_wrapper" class="dataTables_wrapper">
    <table id="oppgave-table" class="display">
      <thead>
        <tr>
          {if $display_type eq 'contact'}
            <th id="nosort">{ts}Year{/ts}</th>
          {/if}
          {if $display_type eq 'year'}
            <th>{ts}Contact ID{/ts}</th>
          {/if}
          <th>{ts}Donor Type{/ts}</th>
          <th>{ts}Donor Name{/ts}</th>
          <th>{ts}Donor Number{/ts}</th>
          <th>{ts}Deductible Amount{/ts}</th>
          <th>{ts}Date Loaded{/ts}</th>
          <th>{ts}Date Modified{/ts}</th>
          <th>{ts}Modified By{/ts}</th>
          <th>{ts}Date Exported{/ts}</th>
          <th id="nosort"></th>
        </tr>
      </thead>
      <tbody>
        {assign var="row_class" value="odd-row"}
          {foreach from=$oppgaves key=oppgave_id item=oppgave}
          <tr id="row1" class={$row_class}>
            {if $display_type eq 'contact'}
              <td>{$oppgave.oppgave_year}</td>
            {/if}
            {if $display_type eq 'year'}
              <td>{$oppgave.contact_id}</td>
            {/if}
            <td>{$oppgave.donor_type}</td>
            <td>{$oppgave.donor_name}</td>
            <td>{$oppgave.donor_number}</td>
            <td>{$oppgave.deductible_amount}</td>
            <td>{$oppgave.loaded_date}</td>
            <td>{$oppgave.last_modified_date}</td>
            <td>{$oppgave.last_modified_user}</td>
            <td>{$oppgave.last_exported_date}</td>
            <td>
              <span>
                {foreach from=$oppgave.actions item=action_link}
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
  {if $display_type eq 'year'}
    <div class="action-link">
      <a class="button new-option" href="{$add_url}">
        <span><div class="icon add-icon"></div>Ny Donoroppgave</span>
      </a>
    </div>
  {/if}
</div>
