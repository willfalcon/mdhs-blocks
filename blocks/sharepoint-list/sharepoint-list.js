import { TabulatorFull as Tabulator } from 'tabulator-tables';
// Tabulator

// Sorting parameters to be added to the Columns Array
function getSorter({ sortable, data }) {
  if (sortable) {
    if (data.dateTime) {
      return {
        sorter: 'date',
        sorterParams: {
          format: 'iso',
        },
      };
    }
    return {
      sorter: 'string',
    };
  }
  return {};
}

// Formatting parameters to be added to the columns array
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

// Init Table
async function initTable(wrapper) {
  if (wrapper) {
    const tableId = wrapper.dataset.table;

    const data = await fetch(`/wp-json/sharepoint/v1/table?id=${tableId}`).then(res => res.json());
    // console.log(data);

    // didn't fetch data for some reason
    if (data === 'Something went wrong') {
      wrapper.innerHTML = `<p style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);">${wp.i18n.__(
        'Something went wrong. Please try again later.'
      )}</p>`;
      return;
    }

    // filter out hidden columns, marked hidden in the table settings in WP.
    const hideColumns = wrapper.dataset.hideColumns ? JSON.parse(wrapper.dataset.hideColumns) : null;
    // console.log(hideColumns);
    const hideColumnsArr = hideColumns?.map(col => col.sharepoint_column);
    const filteredColumns = hideColumns
      ? data.mappedColumns.filter(col => !hideColumnsArr.includes(col.sharepoint_column))
      : data.mappedColumns;

    // build out columns array with sorting and formatting info.
    const columns = filteredColumns.map(col => ({
      field: col.data.name,
      title: col.column_title || col.data.displayName,
      // sorter: col.sortable ? getSorter(col.data) : null,
      ...getSorter(col),
      ...format(col.data, col.long_text_field, col.document_field, col.document_name_column),
    }));

    // Filter rows based on backend table setting filters
    const filters = wrapper.dataset.filters ? JSON.parse(wrapper.dataset.filters) : null;

    function filterRows(rows) {
      if (!filters) {
        return rows;
      }

      return rows.filter(row => filters.some(filter => row[filter.sharepoint_column] == filter.equals));
    }
    const unfilteredRows = data.items.map(item => item.fields);
    const rows = filterRows(unfilteredRows);

    // Setup table
    const table = new Tabulator(wrapper, {
      data: rows,
      columns: columns,
    });
    table.on('tableBuilt', () => {
      wrapper.classList.remove('loading');
    });
    table.on('dataSorting', function (sorters) {
      console.log(sorters);
    });
    table.on('dataSorted', function (sorters, rows) {
      console.log(rows);
    });

    // Setup search box
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

    // Setup download button
    const downloadButton = document.querySelector(`#download-${tableId}`);
    if (downloadButton) {
      Tabulator.extendModule;
      downloadButton.addEventListener('click', e => {
        table.downloadToTab('pdf', 'Open Procurements.pdf', {
          orientation: 'landscape',
          title: 'Open Procurements',
          autoTable: {
            styles: {
              minCellWidth: 50,
            },
          },
        });
      });
    }
  }
}

const wrappers = document.querySelectorAll('.sharepoint-list');
wrappers.forEach(initTable);
