export default function filterRows(rows, dataset) {
  const { filterField, filterCondition, filterValue } = dataset;
  const filtered = rows.filter(row => {
    return row.cells.find(cell => cell.columnId == parseInt(filterField)).value === filterValue;
  });
  return filtered;
}
