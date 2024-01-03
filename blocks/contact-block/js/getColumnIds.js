export default async function getColumnIds(id, columns) {
  const contactCard = await fetch(`/wp-json/mdhs/v1/get-field/${id}/contact_card`).then(res => res.json());

  const districtColumnId = columns.find(col => col.id == contactCard.primary_label)?.id;
  const numberColumnId = columns.find(col => col.id == contactCard.phone)?.id;
  const addressColumnId = columns.find(col => col.id == contactCard.address_line_1)?.id;
  const addressColumn2Id = columns.find(col => col.id == contactCard.address_line_2)?.id;
  const emailColumnId = columns.find(col => col.id == contactCard.email)?.id;
  const websiteColumnId = columns.find(col => col.id == contactCard.website)?.id;
  const iconsColumnId = columns.find(col => col.id == contactCard.icons_column)?.id;
  return {
    districtColumnId,
    numberColumnId,
    addressColumnId,
    addressColumn2Id,
    emailColumnId,
    websiteColumnId,
    iconsColumnId,
  };
}
