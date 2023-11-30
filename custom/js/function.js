// Function to compress images
function compressImage(file) {
  return new Promise((resolve, reject) => {
    new Compressor(file, {
      quality: 0.8, // Adjust quality as needed
      success(result) {
        resolve(result);
      },
      error(e) {
        reject(e);
      },
    });
  });
}