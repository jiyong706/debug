import cv2
import sys
import os
import easyocr

def process_image(image_path, output_path, overlay_path, x, y, width, height):
    print(f"Input image path: {image_path}")
    print(f"Output image path: {output_path}")
    print(f"Overlay image path: {overlay_path}")
    print(f"Selected region: x={x}, y={y}, width={width}, height={height}")

    if not os.path.isfile(image_path):
        print(f"Error: {image_path} does not exist or is not a file.")
        return

    image = cv2.imread(image_path)
    
    if image is None:
        print(f"Error: Unable to read image from {image_path}")
        return

    print(f"Image read successfully from {image_path}")

    # Adjust selected region coordinates if necessary
    if x < 0: x = 0
    if y < 0: y = 0
    if x + width > image.shape[1]: width = image.shape[1] - x
    if y + height > image.shape[0]: height = image.shape[0] - y

    # Extract selected region
    selected_region = image[y:y+height, x:x+width]
    
    # 텍스트 추출 영역을 네모 박스로 표시
    overlay_image = image.copy()
    cv2.rectangle(overlay_image, (x, y), (x+width, y+height), (0, 255, 0), 2)  # Green rectangle with thickness 2

    # 이미지 쓰기
    success = cv2.imwrite(output_path, selected_region)
    overlay_success = cv2.imwrite(overlay_path, overlay_image)
    
    if not success:
        print(f"Error: Unable to write image to {output_path}")
    else:
        print(f"Success: Image written to {output_path}")

    if not overlay_success:
        print(f"Error: Unable to write overlay image to {overlay_path}")
    else:
        print(f"Success: Overlay image written to {overlay_path}")

    # EasyOCR로 텍스트 추출
    reader = easyocr.Reader(['ko', 'en'])
    result = reader.readtext(output_path)

    extracted_text = "\n".join([res[1] for res in result])
    print(extracted_text)

    # 추출된 텍스트를 파일에 저장
    with open("extracted_text.txt", "w", encoding="utf-8") as f:
        f.write(extracted_text)

if __name__ == "__main__":
    if len(sys.argv) != 8:
        print("Usage: python image_processing.py <input_image> <output_image> <overlay_image> <x> <y> <width> <height>")
    else:
        process_image(sys.argv[1], sys.argv[2], sys.argv[3], int(sys.argv[4]), int(sys.argv[5]), int(sys.argv[6]), int(sys.argv[7]))
