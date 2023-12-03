function handleResponse(xhr, idx, action) {
  if (xhr.status == 200) {
    try {
    const data = JSON.parse(xhr.responseText);
    if (data.result == 'success') {
      alert(`Banner ${action} successfully!`);
      self.location.href = '/manage.php';
    } else {
      alert(`Failed to ${action}: ${data.message}`);
    }
    } catch (error) {
      console.error('Error parsing JSON:', error);
    }
  } else {
      alert(`Error: ${xhr.status}`);
  }
}