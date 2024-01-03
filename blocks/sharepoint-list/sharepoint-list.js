import { TabulatorFull as Tabulator } from 'tabulator-tables';

// Tabulator

function getSorter(data) {
  if (data.dateTime) {
    return 'date';
  }
  return 'string';
}

function format(data, longText, documentField, documentName) {
  if (data.dateTime) {
    return {
      formatter: 'datetime',
      formatterParams: {
        inputFormat: 'iso',
        outputFormat: 'M/d/yyyy',
      },
    };
  }
  if (data.currency) {
    return {
      formatter: 'money',
      formatterParams: {
        symbol: '$',
        precision: false,
      },
    };
  }
  if (longText) {
    return {
      formatter: 'textarea',
      width: 400,
    };
  }
  if (documentField) {
    return {
      mutator: function (value, data, type, params, component) {
        if (value && params.documentName) {
          return `<a href="${value}" target="_blank">${data[params.documentName]}</a>`;
        }
        if (value) {
          return `<a href="${value}" target="_blank">Document</a>`;
        }

        return value;
      },
      mutatorParams: { documentName },
      formatter: 'html',
    };
  }
  return {};
}

async function initTable(wrapper) {
  if (wrapper) {
    const tableId = wrapper.dataset.table;

    const data = await fetch(`/wp-json/sharepoint/v1/table?id=${tableId}`).then(res => res.json());
    // console.log(data);
    const hideColumns = wrapper.dataset.hideColumns ? JSON.parse(wrapper.dataset.hideColumns) : null;
    // console.log(hideColumns);
    const hideColumnsArr = hideColumns?.map(col => col.sharepoint_column);
    const filteredColumns = hideColumns
      ? data.mappedColumns.filter(col => !hideColumnsArr.includes(col.sharepoint_column))
      : data.mappedColumns;
    const columns = filteredColumns.map(col => ({
      field: col.data.name,
      title: col.column_title || col.data.displayName,
      sorter: col.sortable ? getSorter(col.data) : null,
      ...format(col.data, col.long_text_field, col.document_field, col.document_name_column),
    }));

    const filters = wrapper.dataset.filters ? JSON.parse(wrapper.dataset.filters) : null;

    function filterRows(rows) {
      if (!filters) {
        return rows;
      }

      return rows.filter(row => filters.some(filter => row[filter.sharepoint_column] == filter.equals));
    }
    const unfilteredRows = data.items.map(item => item.fields);
    const rows = filterRows(unfilteredRows);

    const table = new Tabulator(wrapper, {
      data: rows,
      columns: columns,
    });
    table.on('tableBuilt', () => {
      wrapper.classList.remove('loading');
    });

    const searchColumns = data.mappedColumns.filter(col => col.include_in_search).map(col => col.sharepoint_column);

    const searchForm = document.querySelector(`.sharepoint-list-search[data-table="${tableId}"]`);
    searchForm.addEventListener('submit', e => {
      e.preventDefault();
      const searchBox = document.querySelector(`.sharepoint-list-search[data-table="${tableId}"] .sharepoint-list-search-box`);
      table.clearFilter();
      if (searchBox.value) {
        table.setFilter([
          searchColumns.map(col => ({
            field: col,
            type: 'keywords',
            value: searchBox.value,
          })),
        ]);
      }
    });
  }
}

const wrappers = document.querySelectorAll('.sharepoint-list');
wrappers.forEach(initTable);
