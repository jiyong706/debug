import cv2
import sys
import os

def process_image(image_path, output_path, overlay_path):
    print(f"Input image path: {image_path}")
    print(f"Output image path: {output_path}")
    print(f"Overlay image path: {overlay_path}")

    if not os.path.isfile(image_path):
        print(f"Error: {image_path} does not exist or is not a file.")
        return

    image = cv2.imread(image_path)
    
    if image is None:
        print(f"Error: Unable to read image from {image_path}")
        return

    print(f"Image read successfully from {image_path}")

    # Convert to grayscale and apply adaptive thresholding
    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
    processed_image = cv2.adaptiveThreshold(gray, 255, cv2.ADAPTIVE_THRESH_GAUSSIAN_C, cv2.THRESH_BINARY, 11, 2)

    # 약 정보가 위치한 영역 (예시로 설정, 실제 위치에 맞게 조정 필요)
    x, y, w, h = 30, 200, 300, 1000  # x, y, width, height
    drug_info_region = processed_image[y:y+h, x:x+w]
    
    # 텍스트 추출 영역을 네모 박스로 표시
    overlay_image = image.copy()
    cv2.rectangle(overlay_image, (x, y), (x+w, y+h), (0, 255, 0), 2)  # Green rectangle with thickness 2

    # 이미지 쓰기
    success = cv2.imwrite(output_path, drug_info_region)
    overlay_success = cv2.imwrite(overlay_path, overlay_image)
    
    if not success:
        print(f"Error: Unable to write image to {output_path}")
    else:
        print(f"Success: Image written to {output_path}")

    if not overlay_success:
        print(f"Error: Unable to write overlay image to {overlay_path}")
    else:
        print(f"Success: Overlay image written to {overlay_path}")

if __name__ == "__main__":
    if len(sys.argv) != 4:
        print("Usage: python image_processing.py <input_image> <output_image> <overlay_image>")
    else:
        process_image(sys.argv[1], sys.argv[2], sys.argv[3])
