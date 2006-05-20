{*
 * stats_table.tpl
 * CS161 project: Bookstore
 * Component: Database stats single table template
 *
 * Copyright (C) 2006 Christopher Han <cfh@gwu.edu>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Library General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 *}
<p>
<strong>Table: </strong>{$table.Name}<br />
{if $table.Engine}
<strong>Engine: </strong>{$table.Engine}<br />
{/if}
{if $table.Type}
<strong>Type: </strong>{$table.Type}<br />
{/if}
{if $table.Version}
<strong>Version: </strong>{$table.Version}<br />
{/if}
{if $table.Row_format}
<strong>Row format: </strong>{$table.Row_format}<br />
{/if}
{if $table.Rows}
<strong>Rows: </strong>{$table.Rows}<br />
{/if}
{if $table.Avg_row_length}
<strong>Average row length: </strong>{$table.Avg_row_length}<br />
{/if}
{if $table.Data_length}
<strong>Data length: </strong>{$table.Data_length}<br />
{/if}
{if $table.Max_data_length}
<strong>Max data length: </strong>{$table.Max_data_length}<br />
{/if}
{if $table.Index_length}
<strong>Index length: </strong>{$table.Index_length}<br />
{/if}
{if $table.Data_free}
<strong>Data free: </strong>{$table.Data_free}<br />
{/if}
{if $table.Auto_increment}
<strong>Auto increment: </strong>{$table.Auto_increment}<br />
{/if}
{if $table.Create_time}
<strong>Create time: </strong>{$table.Create_time}<br />
{/if}
{if $table.Update_time}
<strong>Update time: </strong>{$table.Update_time}<br />
{/if}
{if $table.Check_time}
<strong>Check time: </strong>{$table.Check_time}<br />
{/if}
{if $table.Collation}
<strong>Collation: </strong>{$table.Collation}<br />
{/if}
{if $table.Checksum}
<strong>Checksum: </strong>{$table.Checksum}<br />
{/if}
{if $table.Create_options}
<strong>Create options: </strong>{$table.Create_options}<br />
{/if}
{if $table.Comment}
<strong>Comment: </strong>{$table.Comment}<br />
{/if}
{if $total_size}
<strong>Total size: </strong>{$total_size}<br />
{/if}
</p>
