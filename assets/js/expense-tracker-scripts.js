function formatDateTime(dateTime, format) {
  let dateFormat = format;
  const date = new Date(dateTime);

  const day = String(date.getDate()).padStart(2, "0");
  // alert(year + '-' + month + '-' + day);
  if (dateFormat.includes("Y")) {
    year = date.getFullYear();
  } else {
    dateFormat = dateFormat.replace("y", "Y");
    year = String(date.getFullYear()).slice(-2);
  }
  if (dateFormat.includes("m")) {
    dateFormat = dateFormat.replace("m", "M");
    month = String(date.getMonth() + 1).padStart(2, "0");
  } else if (dateFormat.includes("F")) {
    dateFormat = dateFormat.replace("F", "M");
    month = date.toLocaleString(undefined, {
      month: "long",
    });
  } else {
    month = date.toLocaleString(undefined, {
      month: "short",
    });
    // alert(month);
  }
  dateFormat = dateFormat
    .replace("Y", year)
    .replace("d", day)
    .replace("j", day)
    .replace("M", month);

  return dateFormat;
}
