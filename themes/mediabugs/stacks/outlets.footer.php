	<tr>
		<td colspan="3">
			<? if ($this->hasPreviousPage()) { echo '<a href="?offset=' . $this->previousPage() . $additional_parameters . '" class="stack_previous_link">Previous</a>'; } ?>
			<? if ($this->hasNextPage()) { echo '<a href="?offset=' . $this->nextPage() . $additional_parameters . '" class="stack_next_link">Next</a>'; }	?>
		</td>
	</tr>
</table>