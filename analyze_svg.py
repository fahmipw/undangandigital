import re
import sys

def fix_svg_smooth(filename):
    with open(filename, 'r', encoding='utf-8') as f:
        content = f.read()

    # First, let's remove any previous injected tags just in case
    content = re.sub(r'<animateTransform repeatCount="indefinite" type="rotate" attributeName="transform" dur="4\.009s"[^>]*fill="freeze" />', '', content)
    content = re.sub(r'<animateTransform repeatCount="indefinite" type="rotate" attributeName="transform" dur="8s"[^>]*fill="freeze" />', '', content)

    # Let's verify all repeatCounts for intro are "1"
    content = content.replace('repeatCount="indefinite"', 'repeatCount="1"')

    # Make sure fill="freeze" is present
    def add_freeze(match):
        tag = match.group(0)
        if 'fill="freeze"' not in tag:
            tag = tag.replace('/>', ' fill="freeze" />')
        return tag

    content = re.sub(r'<animate\s+[^>]*/>', add_freeze, content)
    content = re.sub(r'<animateTransform\s+[^>]*/>', add_freeze, content)

    def replacer(match):
        orig_tag = match.group(0)
        
        if 'dur="8s"' in orig_tag:
            return orig_tag

        val_match = re.search(r'values="([^"]+)"', orig_tag)
        if not val_match:
            return orig_tag
            
        values_str = val_match.group(1)
        vals = [v.strip() for v in values_str.split(';')]
        
        if len(vals) < 4:
            return orig_tag
            
        sway_floats = []
        for v in vals[-4:]:
            try:
                sway_floats.append(float(v))
            except:
                pass
                
        if len(sway_floats) < 2:
            return orig_tag
            
        max_val = max(sway_floats)
        min_val = min(sway_floats)
        
        smooth_vals = f"0; {max_val}; 0; {min_val}; 0"
        
        dur_match = re.search(r'dur="([^"]+)"', orig_tag)
        orig_dur = dur_match.group(1) if dur_match else "9.009s"
        
        new_tag = (f'<animateTransform repeatCount="indefinite" type="rotate" '
                   f'attributeName="transform" dur="8s" begin="{orig_dur}" calcMode="spline" '
                   f'values="{smooth_vals}" keyTimes="0; 0.25; 0.5; 0.75; 1" '
                   f'keySplines="0.42 0 0.58 1; 0.42 0 0.58 1; 0.42 0 0.58 1; 0.42 0 0.58 1" fill="freeze" />')
                   
        return orig_tag + new_tag

    content = re.sub(r'<animateTransform[^>]*type="rotate"[^>]*/>', replacer, content)

    with open(filename, 'w', encoding='utf-8') as f:
        f.write(content)
        
    print(f"Smooth SVG generated successfully for {filename}!")
    print('Injected loops:', content.count('dur="8s"'))

if __name__ == '__main__':
    fix_svg_smooth('public/bingkai_animasi.svg')
